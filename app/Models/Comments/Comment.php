<?php

declare(strict_types=1);

namespace App\Models\Comments;

use App\Models\User;
use App\Contracts\Reactable;
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

class Comment extends Model implements Reactable
{
    use SoftDeletes;
    use HasLikeable;

    /* ----------------------------- Mass Assignment ----------------------------- */
    protected $fillable = [
        'user_id',
        'commentable_type',
        'commentable_id',
        'parent_id',
        'reply_count',
        'comment',
        'ip_address',
        'comment_hash',
        'edited_at',
        'like_count',
        'dislike_count',
        'spam_score',
        'is_shadowbanned',
    ];

    /* ----------------------------- Casts ----------------------------- */
    protected $casts = [
        'edited_at'     => 'datetime',
        'reply_count'   => 'integer',
        'like_count'    => 'integer',
        'dislike_count' => 'integer',
        'spam_score'    => 'integer',
        'is_shadowbanned' => 'boolean',
    ];

    /* ----------------------------- Relationships ----------------------------- */

    /**
     * The user who authored this comment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Parent comment if this is a reply.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Replies to this comment.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->latest();
    }

    /**
     * The model this comment is attached to (post, article, etc.).
     */
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Reactions (likes/dislikes) handled by HasReactions trait.
     * This overrides the abstract method required by the trait.
     */
    public function reactions(): MorphMany
    {
        return $this->morphMany(Likeable::class, 'likeable');
    }

    /* ----------------------------- Scopes ----------------------------- */

    /**
     * Only top-level comments (not replies).
     */
    public function scopeTopLevel(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    /* ----------------------------- Attributes ----------------------------- */

    /**
     * Author's display name.
     */
    public function getAuthorNameAttribute(): string
    {
        return $this->user->name;
    }

    /**
     * All replies as a collection.
     */
    public function getAllRepliesAttribute(): Collection
    {
        return $this->replies;
    }

    /* ----------------------------- Helpers ----------------------------- */

    /**
     * Check if this comment is a reply.
     */
    public function isReply(): bool
    {
        return $this->parent_id !== null;
    }

    /**
     * Check if this comment has replies.
     */
    public function hasReplies(): bool
    {
        return $this->reply_count > 0;
    }

    /**
     * Mark comment as edited.
     */
    public function markEdited(): void
    {
        $this->forceFill([
            'edited_at' => now(),
        ])->save();
    }

    /**
     * Increment reply count.
     */
    public function incrementReplyCount(int $by = 1): void
    {
        $this->increment('reply_count', $by);
    }

    /**
     * Add a reply to this comment.
     */
    public function addReply(self $reply): self
    {
        $reply->forceFill([
            'parent_id' => $this->id,
        ])->save();

        $this->incrementReplyCount();

        return $reply;
    }
}
