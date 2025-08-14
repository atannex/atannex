<?php

namespace App\Livewire\Forms;

use App\Models\Comments\Comment as CommentModel;
use App\Rules\Auth\StrongName;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Class Comment
 *
 * A Livewire component for managing comments on a post, including creating, editing,
 * deleting, and replying to comments with pagination and search functionality.
 */
class Comment extends Component
{
    use WithPagination;

    /**
     * The comment text input by the user.
     *
     * @var string|null
     */
    #[Rule(['required', 'min:3', new StrongName], as: 'comment')]
    public ?string $comment = null;

    /**
     * The ID of the post the comments belong to.
     *
     * @var int
     */
    public int $postId;

    /**
     * The ID of the parent comment (for replies).
     *
     * @var int|null
     */
    public ?int $parentId = null;

    /**
     * The ID of the comment being edited (if any).
     *
     * @var int|null
     */
    public ?int $editingCommentId = null;

    /**
     * The search query for filtering comments.
     *
     * @var string
     */
    public string $search = '';

    /**
     * The number of comments to display per page.
     *
     * @var int
     */
    public int $perPage = 5;

    /**
     * Initialize the component with the post ID.
     *
     * @param int $postId
     * @return void
     */
    public function mount(int $postId): void
    {
        $this->postId = $postId;
    }

    /**
     * Validate the comment input when it is updated.
     *
     * @return void
     */
    public function updatedComment(): void
    {
        $this->validateOnly('comment');
    }

    /**
     * Reset pagination when the search query is updated.
     *
     * @return void
     */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Submit the comment form, either creating a new comment or updating an existing one.
     *
     * @return void
     */
    public function submit(): void
    {
        $this->validate();

        $this->editingCommentId ? $this->updateComment() : $this->createComment();

        $this->resetCommentState();
        $this->resetPage();
        $this->search = '';

        session()->flash('message', $this->editingCommentId ? 'Comment updated!' : 'Comment posted successfully!');
        $this->dispatch('comment-posted');
    }

    /**
     * Create a new comment in the database.
     *
     * @return void
     */
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

    /**
     * Update an existing comment in the database.
     *
     * @return void
     */
    protected function updateComment(): void
    {
        $comment = CommentModel::findOrFail($this->editingCommentId);

        $this->authorize('update', $comment);

        $comment->update([
            'comment'     => $this->comment,
            'edited_at'   => now(),
            'edited_by'   => Auth::id(),
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
        ]);
    }

    /**
     * Prepare the form for editing an existing comment.
     *
     * @param int $commentId
     * @return void
     */
    public function edit(int $commentId): void
    {
        $comment = CommentModel::findOrFail($commentId);

        $this->authorize('update', $comment);

        $this->editingCommentId = $comment->id;
        $this->comment = $comment->comment;
        $this->parentId = $comment->parent_id;

        $this->dispatch('focus-comment-input');
    }

    /**
     * Delete a comment and mark the user who deleted it.
     *
     * @param int $commentId
     * @return void
     */
    public function delete(int $commentId): void
    {
        $comment = CommentModel::findOrFail($commentId);

        $this->authorize('delete', $comment);

        $comment->update(['deleted_by' => Auth::id()]);
        $comment->delete();

        session()->flash('message', 'Comment deleted.');
        $this->resetPage();
    }

    /**
     * Set the form to reply to a specific comment.
     *
     * @param int $commentId
     * @return void
     */
    #[On('reply-to-comment')]
    public function setReplyTo(int $commentId): void
    {
        $this->parentId = $commentId;
        $this->comment = '';
        $this->editingCommentId = null;

        $this->dispatch('focus-comment-input');
    }

    /**
     * Refresh the comments list when a comment is posted.
     *
     * @return void
     */
    #[On('comment-posted')]
    public function refreshComments(): void
    {
        $this->resetPage();
    }

    /**
     * Cancel the reply or edit mode and reset the form.
     *
     * @return void
     */
    public function cancelReply(): void
    {
        $this->resetCommentState();
    }

    /**
     * Reset the comment form state to its initial values.
     *
     * @return void
     */
    protected function resetCommentState(): void
    {
        $this->reset(['comment', 'parentId', 'editingCommentId']);
    }

    /**
     * Render the comment component view with paginated comments.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        $comments = CommentModel::approved()
            ->topLevel()
            ->withAllReplies()
            ->where('post_id', $this->postId)
            ->when($this->search, fn ($query) => $query->where('comment', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.forms.comment', [
            'comments' => $comments,
        ]);
    }
}
