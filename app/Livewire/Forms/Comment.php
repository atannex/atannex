<?php

namespace App\Livewire\Forms;

use Livewire\Component;
use Livewire\Attributes\Rule;
use App\Contracts\Commentable;
use App\Livewire\Comments\CommentCrud;
use App\Livewire\Comments\CommentReply;
use App\Livewire\Comments\CommentPagination;
use App\Livewire\Comments\CommentSubmission;
use App\Livewire\Comments\CommentAuthorization;
use App\Models\Comments\Comment as CommentModel;

class Comment extends Component
{
    use CommentAuthorization;
    use CommentCrud;
    use CommentPagination;
    use CommentReply;
    use CommentSubmission;

    #[Rule(['required', 'string', 'min:3', 'max:5000'], as: 'comment')]
    public ?string $comment = null;

    public Commentable $commentable;

    public ?int $parentId = null;

    public array $shownRepliesCount = [];

    public function mount(Commentable $commentable): void
    {
        $this->commentable = $commentable;
        $this->shownRepliesCount = [];
    }

    public function render()
    {
        $comments = $this->commentable
            ->comments()
            ->topLevel()
            ->with(['user', 'replies.user', 'replies.parent.user'])
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
