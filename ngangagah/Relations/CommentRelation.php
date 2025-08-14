<?php

namespace Ngangagah\Relations;

use App\Models\User;
use App\Models\Posts\Post;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Trait CommentRelation
 *
 * Defines Eloquent relationships for a comment model, including associations with posts,
 * parent comments, replies, users, and administrative actions (edit, delete, review).
 */
trait CommentRelation
{
    /**
     * Get the post that this comment belongs to.
     *
     * @return BelongsTo
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the parent comment of this comment (if it is a reply).
     *
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Get the replies to this comment.
     *
     * @return HasMany
     */
    public function replies(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * Get all replies to this comment, eager loading nested replies and the associated user.
     *
     * @return HasMany
     */
    public function allReplies(): HasMany
    {
        return $this->replies()->with(['allReplies', 'user']);
    }

    /**
     * Get the user who created this comment.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who last edited this comment.
     *
     * @return BelongsTo
     */
    public function editedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'edited_by');
    }

    /**
     * Get the user who deleted this comment.
     *
     * @return BelongsTo
     */
    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /**
     * Get the user who reviewed this comment.
     *
     * @return BelongsTo
     */
    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
