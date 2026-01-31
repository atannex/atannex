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
use App\Enums\ReactionType;

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
    public array $shownRepliesCount = [];

    public function mount(Commentable $commentable): void
    {
        $this->commentable = $commentable;
    }

    public function render(): View
    {
        return view('livewire.forms.comment-form', [
            'comments'          => $this->loadTopLevelComments(),
            'shownRepliesCount' => $this->shownRepliesCount,
            'totalComments'     => $this->totalComments,
            'replyingTo'        => $this->getReplyingContext(),
        ]);
    }

    /* ---------------- Pagination & Replies ---------------- */

    public function loadMoreComments(): void
    {
        $this->perPage += self::COMMENTS_PER_PAGE;
    }

    public function loadMoreReplies(int $commentId): void
    {
        $total   = Comment::where('parent_id', $commentId)->count();
        $current = $this->shownRepliesCount[$commentId] ?? 0;

        $this->shownRepliesCount[$commentId] = min($current + self::REPLIES_PER_LOAD, $total);
    }

    public function collapseReplies(int $commentId): void
    {
        unset($this->shownRepliesCount[$commentId]);
    }

    /* ---------------- Comment Actions ---------------- */

    public function submit(): void
    {
        $this->validate();
        $this->abortIfRateLimited();
        Gate::authorize('create', Comment::class);

        $comment = $this->createComment();

        if ($comment) {
            $this->incrementParentReplyCount($comment);
            event(new NewComment($comment));

            $this->notifyCommentPosted();
            $this->resetCommentForm();

            session()->flash('message', __('Comment posted successfully.'));
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

    public function cancelReply(): void
    {
        $this->resetCommentState();
    }

    /* ---------------- Reactions (Like / Dislike) ---------------- */

    #[On('like-comment')]
    public function toggleLike(int $commentId): void
    {
        $comment = Comment::findOrFail($commentId);
        $comment->isLikedBy(Auth::user())
            ? $comment->removeReaction(Auth::user())
            : $comment->like(Auth::user());

        $this->dispatch('$refresh');
    }

    #[On('dislike-comment')]
    public function toggleDislike(int $commentId): void
    {
        $comment = Comment::findOrFail($commentId);
        $comment->isDislikedBy(Auth::user())
            ? $comment->removeReaction(Auth::user())
            : $comment->dislike(Auth::user());

        $this->dispatch('$refresh');
    }

    /* ---------------- Helpers ---------------- */

    private function createComment(): ?Comment
    {
        try {
            return Comment::create([
                'user_id'          => Auth::id(),
                'commentable_type' => $this->commentable::class,
                'commentable_id'   => $this->commentable->id,
                'parent_id'        => $this->resolveParentId(),
                'comment'          => $this->sanitize($this->comment),
                'ip_address'       => request()->ip(),
                'reply_count'      => 0,
                'like_count'       => 0,
                'dislike_count'    => 0,
                'comment_hash'     => hash(
                    'sha256',
                    ($this->comment ?? '') . Auth::id() . $this->commentable->id . now()->timestamp
                ),
            ]);
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                $this->addError('comment', __('You have already submitted this comment.'));
                return null;
            }
            throw $e;
        }
    }

    private function incrementParentReplyCount(Comment $comment): void
    {
        if ($comment->parent_id) {
            $parent = Comment::find($comment->parent_id);
            $parent?->increment('reply_count');
        }
    }

    private function notifyCommentPosted(): void
    {
        $this->dispatch(self::EVENT_COMMENT_POSTED);
        $this->dispatch('$refresh');
    }

    private function resetCommentForm(): void
    {
        $this->resetCommentState();
        $this->resetPage();
    }

    protected function resetCommentState(): void
    {
        $this->reset(['comment', 'parentId']);
    }

    private function resolveParentId(): ?int
    {
        return $this->parentId ? Comment::findOrFail($this->parentId)->id : null;
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

    protected function sanitize(string $comment): string
    {
        return clean(trim($comment), 'comment');
    }

    protected function getReplyingContext(): ?array
    {
        if (! $this->parentId) return null;

        $comment = Comment::find($this->parentId);

        return $comment ? [
            'commentId' => $comment->id,
            'username'  => $comment->author_name,
        ] : null;
    }

    protected function loadTopLevelComments()
    {
        return $this->commentable
            ->comments()
            ->topLevel()
            ->with([
                'user',
                'replies' => fn($q) => $q->latest()->take(self::REPLIES_PER_LOAD)->with('user')
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
}
