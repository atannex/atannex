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
    public ?int $editingCommentId = null;
    public string $search = '';
    public int $perPage = 5;

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

        $this->editingCommentId
            ? $this->updateComment()
            : $this->createComment();

        $this->resetCommentState();
        $this->resetPage();
        $this->search = '';

        session()->flash('message', $this->editingCommentId ? 'Comment updated!' : 'Comment posted successfully!');
        $this->dispatch('comment-posted');
    }

    protected function createComment(): void
    {
        CommentModel::create([
            'user_id'    => Auth::id(),
            'post_id'    => $this->postId,
            'parent_id'  => $this->parentId,
            'comment'    => $this->comment,
            'status'     => 'approved',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    protected function updateComment(): void
    {
        $comment = CommentModel::findOrFail($this->editingCommentId);

        $this->authorize('update', $comment);

        $comment->update([
            'comment'   => $this->comment,
            'edited_at' => now(),
            'edited_by' => Auth::id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function edit(int $commentId): void
    {
        $comment = CommentModel::findOrFail($commentId);

        $this->authorize('update', $comment);

        $this->editingCommentId = $comment->id;
        $this->comment = $comment->comment;
        $this->parentId = $comment->parent_id;

        $this->dispatch('focus-comment-input');
    }

    public function delete(int $commentId): void
    {
        $comment = CommentModel::findOrFail($commentId);

        $this->authorize('delete', $comment);

        $comment->update(['deleted_by' => Auth::id()]);
        $comment->delete();

        session()->flash('message', 'Comment deleted.');
        $this->resetPage();
    }

    #[On('reply-to-comment')]
    public function setReplyTo(int $commentId): void
    {
        $this->parentId = $commentId;
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
            'editingCommentId',
        ]);
    }

    public function render()
    {
        $comments = CommentModel::approved()
            ->topLevel()
            ->withAllReplies()
            ->where('post_id', $this->postId)
            ->when($this->search, fn($query) => $query->where('comment', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.forms.comment', [
            'comments' => $comments,
        ]);
    }
}
