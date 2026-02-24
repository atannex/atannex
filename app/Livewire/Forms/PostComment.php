<?php

namespace App\Livewire\Forms;

use App\Models\Comments\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Locked;
use Livewire\Component;

class PostComment extends Component
{
    public Model $commentable;

    public string $newComment = '';

    public string $replyText = '';

    public ?string $replyMention = null;

    public ?int $replyingTo = null;

    public ?int $rootId = null;

    public array $openReplies = [];

    public int $perPage = 10;

    #[Locked]
    public bool $hasMore = false;

    private const COMMENT_MAX = 2;    // 5 comments

    private const COMMENT_TTL = 60;   // per 60 seconds

    private const REPLY_MAX = 10;   // 10 replies

    private const REPLY_TTL = 60;   // per 60 seconds

    private const LIKE_MAX = 30;   // 30 likes

    private const LIKE_TTL = 60;   // per 60 seconds

    public function mount(Model $commentable): void
    {
        $this->commentable = $commentable;
    }

    public function render()
    {
        return view('livewire.forms.post-comment', [
            'comments' => $this->comments,
            'totalCount' => $this->totalCount,
        ]);
    }

    public function getCommentsProperty(): Collection
    {
        $results = Comment::query()
            ->whereMorphedTo('commentable', $this->commentable)
            ->topLevel()
            ->with($this->eagerLoadRelations())
            ->withCount('likes')
            ->latest()
            ->limit($this->perPage + 1)
            ->get();

        $this->hasMore = $results->count() > $this->perPage;

        return $results
            ->take($this->perPage)
            ->map(fn(Comment $c) => $this->formatComment($c, Auth::user()));
    }

    public function getTotalCountProperty(): int
    {
        return Comment::whereMorphedTo('commentable', $this->commentable)->count();
    }

    public function postComment(): void
    {
        $this->requireAuth();
        $this->throttle('comment:' . Auth::id(), self::COMMENT_MAX, self::COMMENT_TTL, 'newComment');
        $this->validate(['newComment' => 'required|string|min:2|max:2000']);

        Comment::create([
            'user_id' => Auth::id(),
            'commentable_type' => get_class($this->commentable),
            'commentable_id' => $this->commentable->getKey(),
            'parent_id' => null,
            'root_id' => null,
            'body' => $this->newComment,
        ]);

        $this->reset('newComment');
    }

    public function postReply(): void
    {
        $this->requireAuth();
        $this->throttle('reply:' . Auth::id(), self::REPLY_MAX, self::REPLY_TTL, 'replyText');
        $this->validate(['replyText' => 'required|string|min:2|max:1000']);

        $parent = Comment::findOrFail($this->replyingTo);
        $rootId = $parent->parent_id === null ? $parent->id : $this->rootId;

        Comment::create([
            'user_id' => Auth::id(),
            'commentable_type' => get_class($this->commentable),
            'commentable_id' => $this->commentable->getKey(),
            'parent_id' => $parent->id,
            'root_id' => $rootId,
            'body' => $this->replyText,
        ]);

        $this->openReplies[$parent->id] = true;

        $this->resetReplyState();
    }

    public function deleteComment(int $commentId): void
    {
        $this->requireAuth();

        $comment = Comment::findOrFail($commentId);

        abort_unless(
            Auth::id() === $comment->user_id || Auth::user()?->is_admin,
            403,
            'You are not authorised to delete this comment.'
        );

        $comment->delete();
    }

    public function toggleLike(int $commentId): void
    {
        $this->requireAuth();
        $this->throttle('like:' . Auth::id(), self::LIKE_MAX, self::LIKE_TTL, 'newComment');

        $comment = Comment::findOrFail($commentId);

        $comment->likes()->where('user_id', Auth::id())->exists()
            ? $comment->likes()->detach(Auth::id())
            : $comment->likes()->attach(Auth::id());
    }

    public function startReply(int $targetId, int $rootId, ?string $mention = null): void
    {
        if ($this->replyingTo === $targetId) {
            $this->resetReplyState();

            return;
        }

        $this->replyingTo = $targetId;
        $this->rootId = $rootId;
        $this->replyMention = $mention;
        $this->replyText = $mention ? "@{$mention} " : '';
    }

    public function toggleReplies(int $commentId): void
    {
        if (isset($this->openReplies[$commentId])) {
            unset($this->openReplies[$commentId]);
        } else {
            $this->openReplies[$commentId] = true;
        }
    }

    public function loadMore(): void
    {
        $this->perPage += 10;
    }

    public function renderBody(string $body): string
    {
        return preg_replace_callback(
            '/@([A-Za-z][A-Za-z0-9]*)(?:\s([A-Za-z][A-Za-z0-9]*)(?:\s([A-Za-z][A-Za-z0-9]*))?)?/',
            static function (array $m): string {
                $name = $m[1];
                if (! empty($m[2])) {
                    $name .= ' ' . $m[2];
                }
                if (! empty($m[3])) {
                    $name .= ' ' . $m[3];
                }

                return '<span class="fb-at">@' . e($name) . '</span>';
            },
            e($body)
        );
    }

    private function formatComment(Comment $comment, ?object $user): array
    {
        return [
            'id' => $comment->id,
            'name' => $comment->user->name,
            'avatar' => $this->avatarUrl($comment->user),
            'body' => $comment->body,
            'time' => $comment->created_at->diffForHumans(),
            'likesCount' => $comment->likes_count,
            'isLiked' => $user ? $comment->likes->contains('id', $user->id) : false,
            'isOwner' => $user?->id === $comment->user_id,
            'replies' => $comment->directReplies
                ->map(fn(Comment $r) => $this->formatReply($r, $comment->id, $user))
                ->all(),
        ];
    }

    private function formatReply(Comment $reply, int $rootId, ?object $user): array
    {
        return [
            'id' => $reply->id,
            'rootId' => $rootId,
            'name' => $reply->user->name,
            'avatar' => $this->avatarUrl($reply->user),
            'body' => $reply->body,
            'time' => $reply->created_at->diffForHumans(),
            'likesCount' => $reply->likes_count ?? $reply->likes->count(),
            'isLiked' => $user ? $reply->likes->contains('id', $user->id) : false,
            'isOwner' => $user?->id === $reply->user_id,
            'subReplies' => $reply->directReplies
                ->map(fn(Comment $s) => $this->formatReply($s, $rootId, $user))
                ->all(),
        ];
    }

    private function avatarUrl(object $user): string
    {
        if (!empty($user->image) && file_exists(public_path('storage/' . $user->image))) {
            return asset('storage/' . $user->image);
        }

        return 'https://ui-avatars.com/api/?name='
            . urlencode($user->name)
            . '&background=2d88ff&color=fff&bold=true';
    }

    private function eagerLoadRelations(): array
    {
        return [
            'user',
            'likes',
            'directReplies.user',
            'directReplies.likes',
            'directReplies.directReplies.user',
            'directReplies.directReplies.likes',
        ];
    }

    private function requireAuth(): void
    {
        abort_unless(Auth::check(), 401, 'You must be logged in.');
    }

    private function throttle(string $key, int $maxAttempts, int $decaySeconds, string $field): void
    {
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);

            $this->addError(
                $field,
                "Slow down! You can try again in {$seconds} " . ($seconds === 1 ? 'second' : 'seconds') . '.'
            );

            return;
        }

        RateLimiter::hit($key, $decaySeconds);
    }

    private function resetReplyState(): void
    {
        $this->replyingTo = null;
        $this->rootId = null;
        $this->replyMention = null;
        $this->replyText = '';
    }
}
