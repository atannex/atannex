<?php

namespace App\Livewire\Forms;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;
use App\Contracts\Commentable;
use App\Events\NewComment;
use App\Models\Comments\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Atannex\Services\CommentReactionService;

class CommentForm extends Component
{
    use WithPagination;

    protected const COMMENTS_PER_LOAD = 5;
    protected const REPLIES_PER_LOAD  = 3;
    protected const RATE_LIMIT_MAX    = 5;

    public const EVENT_COMMENT_POSTED  = 'comment-posted';
    public const EVENT_COMMENT_DELETED = 'comment-deleted';

    public Commentable $commentable;

    #[Rule(['required', 'string', 'min:3', 'max:5000'], as: 'comment')]
    public ?string $comment = null;

    public ?int $parentId = null;
    public int $perPage = self::COMMENTS_PER_LOAD;

    /** Track how many replies are currently shown per comment */
    public array $shownRepliesCount = [];

    /** Honeypot field for spam protection */
    public ?string $website = null;

    /** Guest fields */
    public ?string $guest_name = null;
    public ?string $guest_email = null;

    public function mount(Commentable $commentable): void
    {
        $this->commentable = $commentable;

        if (Auth::guest()) {
            $this->guest_name  = session('guest_name');
            $this->guest_email = session('guest_email');
        }
    }

    public function render()
    {
        $comments = $this->getTopLevelComments();

        return view('livewire.forms.comment-form', [
            'comments'          => $comments,
            'shownRepliesCount' => $this->shownRepliesCount,
            'replyingTo'        => $this->replyingContext(),
        ]);
    }

    public function loadMoreComments(): void
    {
        $this->perPage += self::COMMENTS_PER_LOAD;
    }

    public function submit(): void
    {
        $this->validate();
        $this->storeGuestSession();

        if ($this->isSpam()) {
            abort(403);
        }

        if ($this->isRateLimited()) {
            $this->addError('comment', __('Too many comments. Please slow down.'));
            return;
        }

        Gate::authorize('create', Comment::class);

        try {
            $comment = Comment::create([
                'user_id'          => Auth::id(),
                'is_guest'         => Auth::guest(),
                'guest_name'       => $this->guestName(),
                'guest_email'      => $this->guestEmail(),
                'commentable_type' => $this->commentable::class,
                'commentable_id'   => $this->commentable->id,
                'parent_id'        => $this->resolveParentId(),
                'comment'          => $this->sanitize($this->comment),
                'ip_address'       => request()->ip(),
                'reply_count'      => 0,
                'like_count'       => 0,
                'dislike_count'    => 0,
                'comment_hash'     => hash('sha256', ($this->comment ?? '') . Auth::id() . $this->commentable->id),
            ]);

            if ($comment->parent_id) {
                $parent = Comment::find($comment->parent_id);
                $parent?->incrementReplyCount();
            }

            event(new NewComment($comment));

            $this->dispatch(self::EVENT_COMMENT_POSTED);
            $this->dispatch('$refresh');

            session()->flash('message', __('Comment posted successfully.'));

            $this->resetCommentState();
            $this->resetPage();
        } catch (\Throwable $e) {
            report($e);
            session()->flash('error', __('Failed to post comment.'));
        }
    }

    #[On('edit-comment')]
    public function editComment(int $commentId): void
    {
        $comment = Comment::findOrFail($commentId);
        Gate::authorize('update', $comment);

        $this->comment  = $comment->comment;
        $this->parentId = $comment->parent_id;

        $this->dispatch('edit-comment-form', commentId: $commentId);
    }

    #[On('delete-comment')]
    public function deleteComment(int $commentId): void
    {
        $comment = Comment::findOrFail($commentId);
        Gate::authorize('delete', $comment);

        $comment->delete();

        $this->dispatch(self::EVENT_COMMENT_DELETED);
        session()->flash('message', __('Comment deleted successfully.'));
        $this->resetPage();
    }

    #[On('reply-to-comment')]
    public function setReplyTo(int $commentId): void
    {
        $comment = Comment::findOrFail($commentId);

        $this->parentId = $comment->id;
        $this->comment  = '@' . $comment->author_name . ' ';
    }

    #[On('like-comment')]
    public function likeComment(int $commentId, CommentReactionService $service): void
    {
        $this->react($commentId, 'like', $service);
    }

    #[On('dislike-comment')]
    public function dislikeComment(int $commentId, CommentReactionService $service): void
    {
        $this->react($commentId, 'dislike', $service);
    }

    protected function react(int $commentId, string $type, CommentReactionService $service): void
    {
        if (! Auth::check()) {
            abort(403, 'Authentication required.');
        }

        $comment = Comment::findOrFail($commentId);
        Gate::authorize('react', $comment);

        $service->react($comment, Auth::user(), $type);

        $this->dispatch('$refresh');
    }

    public function cancelReply(): void
    {
        $this->resetCommentState();
    }

    public function loadMoreReplies(int $commentId): void
    {
        $total   = Comment::where('parent_id', $commentId)->count();
        $current = $this->shownRepliesCount[$commentId] ?? 0;

        $this->shownRepliesCount[$commentId] = min(
            $current + self::REPLIES_PER_LOAD,
            $total
        );
    }

    public function collapseReplies(int $commentId): void
    {
        unset($this->shownRepliesCount[$commentId]);
    }

    protected function getTopLevelComments()
    {
        return $this->commentable
            ->comments()
            ->topLevel()
            ->with(['user', 'replies' => fn($q) => $this->applyRepliesLimit($q)])
            ->latest()
            ->paginate($this->perPage);
    }

    protected function applyRepliesLimit($query)
    {
        return $query->latest()->take(self::REPLIES_PER_LOAD)->with('user');
    }

    protected function resolveParentId(): ?int
    {
        return $this->parentId ? Comment::findOrFail($this->parentId)->id : null;
    }

    protected function replyingContext(): ?array
    {
        if (!$this->parentId) return null;

        $comment = Comment::find($this->parentId);
        return $comment ? [
            'commentId' => $comment->id,
            'username'  => $comment->author_name,
        ] : null;
    }

    protected function isSpam(): bool
    {
        return !empty($this->website);
    }

    protected function guestName(): ?string
    {
        return Auth::check() ? null : $this->guest_name;
    }

    protected function guestEmail(): ?string
    {
        return Auth::check() ? null : $this->guest_email;
    }

    protected function storeGuestSession(): void
    {
        if (Auth::guest()) {
            session([
                'guest_name'  => $this->guest_name,
                'guest_email' => $this->guest_email,
            ]);
        }
    }

    protected function isRateLimited(): bool
    {
        $key = 'comments:' . (Auth::id() ?? request()->ip());
        RateLimiter::hit($key);

        return RateLimiter::tooManyAttempts($key, self::RATE_LIMIT_MAX);
    }

    protected function sanitize(string $comment): string
    {
        return clean(trim($comment), 'comment');
    }

    protected function resetCommentState(): void
    {
        $this->reset(['comment', 'parentId']);
    }
}
