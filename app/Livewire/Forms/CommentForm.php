<?php

namespace App\Livewire\Forms;

use App\Contracts\Commentable;
use App\Models\Comments\Comment;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class CommentForm extends Component
{
    use WithPagination;

    /* -----------------------------------------------------------------
     | Configuration
     | -----------------------------------------------------------------
     */
    protected const COMMENTS_PER_LOAD = 10;
    protected const REPLIES_PER_LOAD  = 4;
    protected const RATE_LIMIT_MAX    = 5;

    public const EVENT_COMMENT_POSTED  = 'comment-posted';
    public const EVENT_COMMENT_DELETED = 'comment-deleted';

    /* -----------------------------------------------------------------
     | State
     | -----------------------------------------------------------------
     */
    public Commentable $commentable;

    #[Rule(['required', 'string', 'min:3', 'max:5000'], as: 'comment')]
    public ?string $comment = null;

    public ?int $parentId = null;

    public int $perPage = self::COMMENTS_PER_LOAD;

    /** @var array<int,int> */
    public array $shownRepliesCount = [];

    /* Honeypot (spam) */
    public ?string $website = null;

    /* Guest fields - needed for binding in guest mode */
    public ?string $guest_name = null;
    public ?string $guest_email = null;

    /* -----------------------------------------------------------------
     | Lifecycle
     | -----------------------------------------------------------------
     */
    public function mount(Commentable $commentable): void
    {
        $this->commentable = $commentable;

        // Pre-fill guest name/email from session if available
        if (Auth::guest()) {
            $this->guest_name  = session('guest_name');
            $this->guest_email = session('guest_email');
        }
    }

    public function render()
    {
        $comments = $this->commentable
            ->comments()
            ->approved()
            ->topLevel()
            ->with([
                'user',
                'replies.user',
                'replies.parent.user',
            ])
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.forms.comment-form', [
            'comments'          => $comments,
            'shownRepliesCount' => $this->shownRepliesCount,
            'replyingTo'        => $this->replyingContext(),
        ]);
    }

    /* -----------------------------------------------------------------
     | Public Actions
     | -----------------------------------------------------------------
     */
    public function loadMoreComments(): void
    {
        $this->perPage += self::COMMENTS_PER_LOAD;
    }

    public function submit(): void
    {
        $this->validate();

        // Store guest info in session before submitting (for future comments)
        if (Auth::guest()) {
            session([
                'guest_name'  => $this->guest_name,
                'guest_email' => $this->guest_email,
            ]);
        }

        /* Spam protection (honeypot) */
        if ($this->isSpam()) {
            abort(403);
        }

        /* Rate limiting */
        $key = 'comments:' . (Auth::id() ?? request()->ip());
        RateLimiter::hit($key);

        if (RateLimiter::tooManyAttempts($key, self::RATE_LIMIT_MAX)) {
            $this->addError('comment', __('Too many comments. Please slow down.'));
            return;
        }

        Gate::authorize('create', Comment::class);

        try {
            Comment::create([
                'user_id'          => Auth::id(),
                'is_guest'         => Auth::guest(),
                'guest_name'       => $this->guestName(),
                'guest_email'      => $this->guestEmail(),
                'guest_token'      => $this->guestToken(),
                'commentable_type' => $this->commentable::class,
                'commentable_id'   => $this->commentable->id,
                'parent_id'        => $this->resolveParentId(),
                'comment'          => $this->sanitize($this->comment ?? ''),
                'ip_address'       => request()->ip(),
                'is_approved'      => true,
            ]);

            $this->dispatch(self::EVENT_COMMENT_POSTED);
            $this->dispatch('$refresh');

            session()->flash('message', __('Comment posted successfully.'));

            $this->resetCommentState();
            $this->resetPage();
        } catch (\Throwable $e) {
            report($e);
            session()->flash('error', __('Failed to post comment.'));
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

        $this->parentId = $comment->parent_id ?: $comment->id;
        $this->comment  = '@' . $comment->author_name . ' ';
    }

    public function cancelReply(): void
    {
        $this->resetCommentState();
    }

    public function loadMoreReplies(int $commentId): void
    {
        $total   = Comment::where('parent_id', $commentId)->approved()->count();
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

    /* -----------------------------------------------------------------
     | Helpers
     | -----------------------------------------------------------------
     */
    protected function authenticatedUser(): ?Authenticatable
    {
        return Auth::user();
    }

    protected function resolveParentId(): ?int
    {
        if (!$this->parentId) {
            return null;
        }

        $target = Comment::findOrFail($this->parentId);

        return $target->parent_id ?: $target->id;
    }

    protected function sanitize(string $comment): string
    {
        return clean(trim($comment), 'comment');
    }

    protected function resetCommentState(): void
    {
        $this->reset(['comment', 'parentId']);
    }

    protected function replyingContext(): ?array
    {
        if (!$this->parentId) {
            return null;
        }

        $comment = Comment::find($this->parentId);

        if (!$comment) {
            return null;
        }

        return [
            'commentId' => $comment->id,
            'username'  => $comment->author_name,
        ];
    }

    /* -----------------------------------------------------------------
     | Guest & Spam Helpers
     | -----------------------------------------------------------------
     */
    protected function isSpam(): bool
    {
        return !empty($this->website);
    }

    protected function guestName(): ?string
    {
        return Auth::check() ? null : $this->guest_name;
    }

    protected function guestEmail(): ?string
    {
        return Auth::check() ? null : $this->guest_email;
    }

    protected function guestToken(): ?string
    {
        if (Auth::check()) {
            return null;
        }

        return session()->get(
            'guest_comment_token',
            tap(bin2hex(random_bytes(32)), fn($token) => session()->put('guest_comment_token', $token))
        );
    }
}
