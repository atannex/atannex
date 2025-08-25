<?php

namespace App\Models\Comments;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Comment
 *
 * Represents a comment in the application, supporting polymorphic relationships
 * to allow comments on multiple types of entities (e.g., posts, articles).
 * Supports nested comments through a parent-child relationship.
 *
 * @package App\Models\Comments
 */
class Comment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',          // The ID of the user who created the comment
        'commentable_type', // The type of the entity the comment is associated with
        'commentable_id',   // The ID of the entity the comment is associated with
        'parent_id',        // The ID of the parent comment for nested replies
        'comment',          // The content of the comment
    ];

    /**
     * Get the parent entity that this comment belongs to (polymorphic relationship).
     */
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the parent comment for this comment (if it is a reply).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Get all replies to this comment.
     * Replies are ordered by the latest first.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id')->latest();
    }

    /**
     * Get the user who created this comment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor to retrieve all replies for this comment.
     * Returns an empty collection if no replies exist.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAllRepliesAttribute()
    {
        return $this->replies ?? collect();
    }

    /**
     * Scope to retrieve top-level comments (comments without a parent).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }
}
