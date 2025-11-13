<?php

namespace App\Livewire\Comments;

use App\Models\Comments\Comment as CommentModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

trait CommentSubmission
{
    const EVENT_COMMENT_POSTED = 'comment-posted';

    protected function sanitizeComment(string $comment): string
    {
        return clean($comment, 'comment');
    }

    public function submit(): void
    {
        $this->validate();

        if (! Auth::check()) {
            session()->flash('error', __('You must be logged in to post a comment.'));

            return;
        }

        try {
            $parentId = null;
            if ($this->parentId) {
                $targetComment = CommentModel::findOrFail($this->parentId);
                $parentId = $targetComment->parent_id ?: $targetComment->id;
            }

            CommentModel::create([
                'user_id' => Auth::id(),
                'commentable_type' => get_class($this->commentable),
                'commentable_id' => $this->commentable->id,
                'parent_id' => $parentId,
                'comment' => $this->sanitizeComment($this->comment),
            ]);

            session()->flash('message', __('Comment posted successfully!'));
            $this->dispatch(self::EVENT_COMMENT_POSTED);
            $this->resetPage();
            $this->resetCommentState();
        } catch (Throwable $throwable) {
            Log::error('Comment submission failed: '.$throwable->getMessage());
            session()->flash('error', __('Failed to post comment. Please try again.'));
        }
    }

    protected function resetCommentState(): void
    {
        $this->reset(['comment', 'parentId']);
    }
}
