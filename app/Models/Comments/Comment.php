<?php

namespace App\Models\Comments;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class Comment
 *
 * Represents a polymorphic, threaded comment.
 * Supports authenticated users and guests, moderation, and spam protection.
 */
class Comment extends Model
{
    /* -----------------------------------------------------------------
     |  Mass Assignment
     | -----------------------------------------------------------------
     */
    protected $fillable = [
        'user_id',
        'is_guest',
        'guest_name',
        'guest_email',
        'guest_token',
        'commentable_type',
        'commentable_id',
        'parent_id',
        'comment',
        'ip_address',
        'is_approved',
        'edited_at',
    ];

    /* -----------------------------------------------------------------
     |  Casting
     | -----------------------------------------------------------------
     */
    protected $casts = [
        'is_guest'    => 'boolean',
        'is_approved' => 'boolean',
        'edited_at'   => 'datetime',
    ];

    /* -----------------------------------------------------------------
     |  Relationships
     | -----------------------------------------------------------------
     */

    /**
     * Polymorphic parent (Post, Article, etc.).
     */
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Authenticated user (nullable for guests).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Parent comment (for replies).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Direct replies to this comment.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')
            ->approved()
            ->latest();
    }

    /* -----------------------------------------------------------------
     |  Scopes
     | -----------------------------------------------------------------
     */

    /**
     * Only top-level comments.
     */
    public function scopeTopLevel(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Only approved comments.
     */
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('is_approved', true);
    }

    /* -----------------------------------------------------------------
     |  Accessors
     | -----------------------------------------------------------------
     */

    /**
     * Unified author name (user or guest).
     */
    public function getAuthorNameAttribute(): string
    {
        return $this->user?->name
            ?? $this->guest_name
            ?? __('Guest');
    }

    /**
     * Safe replies collection.
     */
    public function getAllRepliesAttribute(): Collection
    {
        return $this->replies ?? collect();
    }

    /**
     * Check if the comment belongs to a guest.
     */
    public function getIsGuestCommentAttribute(): bool
    {
        return $this->is_guest && is_null($this->user_id);
    }

    /* -----------------------------------------------------------------
     |  Helpers
     | -----------------------------------------------------------------
     */

    /**
     * Mark comment as edited.
     */
    public function markEdited(): void
    {
        $this->forceFill([
            'edited_at' => now(),
        ])->save();
    }
}
