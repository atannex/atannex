<?php

namespace App\Livewire\Traits;

use App\Models\Comments\Comment as CommentModel;
use Livewire\Attributes\On;

trait CommentReply
{
    #[On('reply-to-comment')]
    public function setReplyTo(int $commentId, string $username): void
    {
        $targetComment = CommentModel::findOrFail($commentId);
        $this->parentId = $targetComment->parent_id ?: $commentId;
        $this->comment = '@' . $username . ' ';
        $this->dispatch('focus-comment-input')->self();
    }

    public function cancelReply(): void
    {
        $this->resetCommentState();
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
}
