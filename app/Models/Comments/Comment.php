<?php

declare(strict_types=1);

namespace App\Models\Comments;

use App\Contracts\Likeably;
use App\Models\User;
use App\Models\Guest;
use App\Models\Comments\Likeable;
use Illuminate\Support\Collection;
use App\Models\Traits\HasLikeable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

class Comment extends Model implements Likeably
{
    use SoftDeletes;
    use HasLikeable;

    protected $fillable = [
        'user_id',
        'guest_id',
        'commentable_type',
        'commentable_id',
        'parent_id',
        'reply_count',
        'comment',
        'name',
        'ip_address',
        'comment_hash',
        'edited_at',
        'like_count',
        'dislike_count',
    ];

    protected $casts = [
        'edited_at'     => 'datetime',
        'reply_count'   => 'integer',
        'like_count'    => 'integer',
        'dislike_count' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $comment) {
            if (!$comment->comment_hash) {
                $comment->comment_hash = self::generateCommentHash($comment);
            }
        });
    }

    public static function generateCommentHash(self $comment): string
    {
        return hash(
            'sha256',
            implode('|', [
                $comment->commentable_type,
                $comment->commentable_id,
                $comment->user_id ?? '',
                $comment->guest_id ?? '',
                Str::limit($comment->comment, 100, ''),
            ])
        );
    }

    /* ----------------------------- Relationships ----------------------------- */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->latest();
    }

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function reactions(): MorphMany
    {
        return $this->morphMany(Likeable::class, 'likeable');
    }

    /* ----------------------------- Scopes ----------------------------- */

    public function scopeTopLevel(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    /* ----------------------------- Attributes ----------------------------- */

    public function getAuthorTypeAttribute(): ?string
    {
        return $this->user_id ? 'user' : ($this->guest_id ? 'guest' : null);
    }

    public function getAuthorIdAttribute(): ?int
    {
        return $this->user_id ?? $this->guest_id;
    }

    public function getAuthorNameAttribute(): string
    {
        if ($this->user) return $this->user->name;
        if ($this->guest) return $this->guest->name ?? 'Guest';
        return 'Unknown';
    }

    public function getAllRepliesAttribute(): Collection
    {
        return $this->replies;
    }

    /* ----------------------------- Helpers ----------------------------- */

    public function isReply(): bool
    {
        return $this->parent_id !== null;
    }

    public function hasReplies(): bool
    {
        return $this->reply_count > 0;
    }

    public function markEdited(): void
    {
        $this->forceFill(['edited_at' => now()])->save();
    }

    public function incrementReplyCount(int $by = 1): void
    {
        $this->increment('reply_count', $by);
    }

    public function addReply(self $reply): self
    {
        $reply->forceFill(['parent_id' => $this->id])->save();
        $this->incrementReplyCount();

        return $reply;
    }
}
