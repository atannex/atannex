<?php

namespace App\Livewire\Traits;

use App\Models\Comments\Comment as CommentModel;
use Livewire\Attributes\On;

trait CommentCrud
{
    const EVENT_COMMENT_DELETED = 'comment-deleted';

    use CommentAuthorization;

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
}
