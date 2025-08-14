<?php

namespace App\Livewire\Forms;

use App\Models\Comments\Comment as CommentModel;
use App\Rules\Auth\StrongName;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Comment extends Component
{
    use WithPagination;

    #[Rule(['required', 'min:3', new StrongName], as: 'comment')]
    public ?string $comment = null;

    public int $postId;
    public ?int $parentId = null;
    public bool $isReplying = false;
    public ?int $replyingToId = null;
    public string $search = '';

    public ?int $editingCommentId = null;
    public int $perPage = 3;

    public function mount(int $postId): void
    {
        $this->postId = $postId;
    }

    public function updatedComment(): void
    {
        $this->validateOnly('comment');
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function submit(): void
    {
        $this->validate();

        $isUpdate = (bool) $this->editingCommentId;

        if ($isUpdate && !$this->isReplying) {
            session()->flash('error', 'Editing can only be done via reply form.');
            return;
        }

        $isUpdate ? $this->updateComment() : $this->createComment();

        $this->resetCommentState();
        $this->resetPage();
        $this->search = '';

        session()->flash('message', $isUpdate ? 'Comment updated!' : 'Comment posted successfully!');

        $this->dispatch('comment-posted', [
            'postId' => $this->postId,
            'parentId' => $this->replyingToId,
            'isUpdate' => $isUpdate,
        ]);
    }

    protected function createComment(): void
    {
        CommentModel::create([
            'user_id'   => Auth::id(),
            'post_id'   => $this->postId,
            'parent_id' => $this->replyingToId,
            'body'      => $this->comment,
        ]);
    }

    protected function updateComment(): void
    {
        $comment = CommentModel::findOrFail($this->editingCommentId);

        $this->authorize('update', $comment);

        $comment->update(['body' => $this->comment]);
    }

    public function edit(int $commentId): void
    {
        $comment = CommentModel::findOrFail($commentId);

        $this->authorize('update', $comment);

        $this->editingCommentId = $comment->id;
        $this->comment = $comment->body;

        $this->isReplying = true;
        $this->parentId = $comment->parent_id;
        $this->replyingToId = $comment->parent_id ?? $comment->id;

        $this->dispatch('focus-comment-input');
    }

    public function delete(int $commentId): void
    {
        $comment = CommentModel::findOrFail($commentId);

        $this->authorize('delete', $comment);

        $comment->delete();

        session()->flash('message', 'Comment deleted.');
        $this->resetPage();
    }

    #[On('reply-to-comment')]
    public function setReplyTo(int $commentId): void
    {
        $this->parentId = $this->replyingToId = $commentId;
        $this->isReplying = true;
        $this->comment = '';
        $this->editingCommentId = null;

        $this->dispatch('focus-comment-input');
    }

    #[On('comment-posted')]
    public function refreshComments(): void
    {
        $this->resetPage();
    }

    public function cancelReply(): void
    {
        $this->resetCommentState();
    }

    protected function resetCommentState(): void
    {
        $this->reset([
            'comment',
            'parentId',
            'isReplying',
            'replyingToId',
            'editingCommentId',
        ]);
    }

    public function render()
    {
        $comments = CommentModel::with(['user', 'children.user', 'post'])
            ->where('post_id', $this->postId)
            ->whereNull('parent_id')
            ->when($this->search, fn($query) => $query->where('body', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.forms.comment', [
            'comments' => $comments,
        ]);
    }
}
