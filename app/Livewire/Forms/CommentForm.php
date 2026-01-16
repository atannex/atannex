<?php

namespace App\Livewire\Forms;

use App\Contracts\Commentable;
use App\Events\CommentPosted;
use App\Models\Comments\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class CommentForm extends Component
{
    use WithPagination;

    protected const COMMENTS_PER_LOAD = 4;
    protected const REPLIES_PER_LOAD  = 2;
    protected const RATE_LIMIT_MAX    = 3;

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

    //  #[Rule([new StrongEmail])]
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
                'guest_token'      => $this->guestToken(),
                'commentable_type' => $this->commentable::class,
                'commentable_id'   => $this->commentable->id,
                'parent_id'        => $this->resolveParentId(),
                'comment'          => $this->sanitize($this->comment ?? ''),
                'ip_address'       => request()->ip(),
                'is_approved'      => true,
            ]);

            event(new CommentPosted($comment));

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

        $this->parentId = $comment->parent_id ?: $comment->id;
        $this->comment  = '@' . $comment->author_name . ' ';
    }

    public function cancelReply(): void
    {
        $this->resetCommentState();
    }

    public function loadMoreReplies(int $commentId): void
    {
        $total   = Comment::where('parent_id', $commentId)->approved()->count();
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
            ->approved()
            ->topLevel()
            ->with(['user', 'replies' => fn($q) => $this->applyRepliesLimit($q)])
            ->latest()
            ->paginate($this->perPage);
    }

    protected function applyRepliesLimit($query)
    {
        return $query
            ->approved()
            ->latest()
            ->take(self::REPLIES_PER_LOAD)
            ->with('user');
    }

    protected function resolveParentId(): ?int
    {
        if (!$this->parentId) return null;

        $target = Comment::findOrFail($this->parentId);
        return $target->parent_id ?: $target->id;
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

    protected function guestToken(): ?string
    {
        if (Auth::check()) return null;

        return session()->get(
            'guest_comment_token',
            tap(bin2hex(random_bytes(32)), fn($token) => session()->put('guest_comment_token', $token))
        );
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
