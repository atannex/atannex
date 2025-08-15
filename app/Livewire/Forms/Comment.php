<?php

namespace App\Livewire\Forms;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;
use App\Contracts\Commentable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Comments\Comment as CommentModel;

class Comment extends Component
{
    use WithPagination;

    #[Rule(['required', 'string', 'min:3', 'max:5000'], as: 'comment')]
    public ?string $comment = null;

    public Commentable $commentable;
    public ?int $parentId = null;
    public array $shownRepliesCount = [];
    public int $perPage = 10;

    const EVENT_COMMENT_POSTED = 'comment-posted';
    const EVENT_COMMENT_DELETED = 'comment-deleted';

    public function mount(Commentable $commentable): void
    {
        $this->commentable = $commentable;
        $this->shownRepliesCount = [];
    }

    public function submit(): void
    {
        $this->validate();

        if (!Auth::check()) {
            session()->flash('error', __('You must be logged in to post a comment.'));
            return;
        }

        try {
            CommentModel::create([
                'user_id'          => Auth::id(),
                'commentable_type' => get_class($this->commentable),
                'commentable_id'   => $this->commentable->id,
                'parent_id'        => $this->parentId,
                'comment'          => $this->sanitizeComment($this->comment),
            ]);

            session()->flash('message', __('Comment posted successfully!'));
            $this->dispatch(self::EVENT_COMMENT_POSTED);
            $this->resetPage();
            $this->resetCommentState();
        } catch (\Throwable $e) {
            Log::error('Comment submission failed: ' . $e->getMessage());
            session()->flash('error', __('Failed to post comment. Please try again.'));
        }
    }

    protected function sanitizeComment(string $comment): string
    {
        return clean($comment, 'comment');
    }

    protected function isAuthorized(CommentModel $comment): bool
    {
        return Auth::id() === $comment->user_id;
    }

    #[On('reply-to-comment')]
    public function setReplyTo(int $commentId, string $username): void
    {
        $this->parentId = $commentId;
        $this->comment = '@' . $username . ' ';
        $this->dispatch('focus-comment-input')->self();
    }

    #[On('edit-comment')]
    public function editComment(int $commentId): void
    {
        $comment = CommentModel::findOrFail($commentId);

        if ($this->isAuthorized($comment)) {
            $this->comment = $comment->comment;
            $this->parentId = $comment->parent_id;
            $this->dispatch('edit-comment-form', ['commentId' => $commentId]);
        } else {
            session()->flash('error', __('You are not authorized to edit this comment.'));
        }
    }

    #[On('delete-comment')]
    public function deleteComment(int $commentId): void
    {
        $comment = CommentModel::findOrFail($commentId);

        if ($this->isAuthorized($comment)) {
            $comment->delete();
            session()->flash('message', __('Comment deleted successfully!'));
            $this->dispatch(self::EVENT_COMMENT_DELETED);
            $this->resetPage();
        } else {
            session()->flash('error', __('You are not authorized to delete this comment.'));
        }
    }

    public function cancelReply(): void
    {
        $this->resetCommentState();
    }

    protected function resetCommentState(): void
    {
        $this->reset(['comment', 'parentId']);
    }

    public function loadMoreReplies(int $commentId): void
    {
        $totalReplies = CommentModel::where('parent_id', $commentId)->count();
        $current = $this->shownRepliesCount[$commentId] ?? 0;
        if ($current < $totalReplies) {
            $this->shownRepliesCount[$commentId] = min($current + 4, $totalReplies);
        }
    }

    public function collapseReplies(int $commentId): void
    {
        $this->shownRepliesCount[$commentId] = 0;
    }

    public function loadMoreComments(): void
    {
        $this->perPage += 10;
    }

    public function render()
    {
        $comments = $this->commentable
            ->comments()
            ->topLevel()
            ->with(['user', 'replies.user', 'replies.parent'])
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.forms.comment', [
            'comments' => $comments,
            'shownRepliesCount' => $this->shownRepliesCount,
            'replyingTo' => $this->parentId ? [
                'commentId' => $this->parentId,
                'username' => CommentModel::find($this->parentId)?->user->name ?? ''
            ] : null,
        ]);
    }
}
