<?php

namespace App\Livewire\Forms;

use Livewire\Component;
use App\Events\NewComment;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;
use App\Contracts\Commentable;
use Livewire\Attributes\Locked;
use App\Models\Comments\Comment;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\RateLimiter;

class CommentForm extends Component
{
    use WithPagination;

    protected const COMMENTS_PER_PAGE = 5;
    protected const REPLIES_PER_LOAD  = 3;
    protected const RATE_LIMIT_MAX    = 5;

    public const EVENT_COMMENT_POSTED  = 'comment-posted';
    public const EVENT_COMMENT_DELETED = 'comment-deleted';

    #[Locked]
    public ?int $parentId = null;

    public Commentable $commentable;

    #[Rule(['required', 'string', 'min:3', 'max:5000'], as: 'comment')]
    public ?string $comment = null;

    public int $perPage = self::COMMENTS_PER_PAGE;

    /**
     * Controls how many replies are visible per parent comment
     */
    public array $shownRepliesCount = [];

    /* ===================== Lifecycle ===================== */

    public function mount(Commentable $commentable): void
    {
        $this->commentable = $commentable;
    }

    public function render(): View
    {
        return view('livewire.forms.comment-form', [
            'comments'          => $this->comments,
            'shownRepliesCount' => $this->shownRepliesCount,
            'totalComments'     => $this->totalComments,
            'replyingTo'        => $this->replyingTo,
        ]);
    }

    /* ===================== Computed Properties ===================== */

    public function getCommentsProperty()
    {
        return $this->commentable
            ->comments()
            ->topLevel()
            ->with([
                'user',
                'replies.user',
                'replies.replies.user',
            ])
            ->latest()
            ->paginate($this->perPage);
    }

    public function getTotalCommentsProperty(): int
    {
        return Comment::where('commentable_type', $this->commentable::class)
            ->where('commentable_id', $this->commentable->id)
            ->count();
    }

    public function getReplyingToProperty(): ?array
    {
        if (! $this->parentId) {
            return null;
        }

        $comment = Comment::find($this->parentId);

        return $comment ? [
            'commentId' => $comment->id,
            'username'  => $comment->author_name,
        ] : null;
    }

    /* ===================== Pagination ===================== */

    public function loadMoreComments(): void
    {
        $this->perPage += self::COMMENTS_PER_PAGE;
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

    /* ===================== Comment Actions ===================== */

    public function submit(): void
    {
        $this->validate();
        $this->abortIfRateLimited();
        Gate::authorize('create', Comment::class);

        $comment = $this->createComment();

        if (! $comment) {
            return;
        }

        // ✅ Ensure visibility immediately
        if ($comment->parent_id) {
            $this->shownRepliesCount[$comment->parent_id] =
                ($this->shownRepliesCount[$comment->parent_id] ?? 0) + 1;
        } else {
            $this->resetPage();
        }

        event(new NewComment($comment));

        $this->resetCommentState();

        // 🔑 Force Livewire re-render
        $this->dispatch('$refresh');
    }

    #[On('delete-comment')]
    public function deleteComment(int $commentId): void
    {
        $comment = Comment::findOrFail($commentId);
        Gate::authorize('delete', $comment);

        $comment->delete();

        unset($this->shownRepliesCount[$commentId]);

        $this->dispatch(self::EVENT_COMMENT_DELETED);
        $this->dispatch('$refresh');
    }

    #[On('reply-to-comment')]
    public function setReplyTo(int $commentId): void
    {
        $comment = Comment::findOrFail($commentId);

        $this->parentId = $comment->id;
        $this->comment  = '@' . $comment->author_name . ' ';
    }

    public function cancelReply(): void
    {
        $this->resetCommentState();
    }

    /* ===================== Reactions ===================== */

    public function toggleLike(int $commentId): void
    {
        $comment = Comment::findOrFail($commentId);

        $comment->isLikedBy(Auth::user())
            ? $comment->removeReaction(Auth::user())
            : $comment->like(Auth::user());

        $this->dispatch('$refresh');
    }

    public function toggleDislike(int $commentId): void
    {
        $comment = Comment::findOrFail($commentId);

        $comment->isDislikedBy(Auth::user())
            ? $comment->removeReaction(Auth::user())
            : $comment->dislike(Auth::user());

        $this->dispatch('$refresh');
    }

    /* ===================== Internals ===================== */

    private function createComment(): ?Comment
    {
        try {
            return Comment::create([
                'user_id'          => Auth::id(),
                'commentable_type' => $this->commentable::class,
                'commentable_id'   => $this->commentable->id,
                'parent_id'        => $this->parentId,
                'comment'          => clean(trim($this->comment), 'comment'),
                'ip_address'       => request()->ip(),
            ]);
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                $this->addError('comment', __('Duplicate comment detected.'));
                return null;
            }

            throw $e;
        }
    }

    private function resetCommentState(): void
    {
        $this->reset(['comment', 'parentId']);
    }

    private function abortIfRateLimited(): void
    {
        $key = 'comments:' . Auth::id();
        RateLimiter::hit($key, 3600);

        if (RateLimiter::tooManyAttempts($key, self::RATE_LIMIT_MAX)) {
            $this->addError('comment', __('Too many comments. Please slow down.'));
            $this->skipRender();
        }
    }
}
