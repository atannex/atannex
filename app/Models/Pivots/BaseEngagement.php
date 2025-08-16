<?php

namespace App\Models\Pivots;

use App\Models\Posts\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Abstract base class for all engagement pivot models.
 *
 * This class provides a standardized way to associate users with posts
 * for various engagement types (e.g., likes, shares, comments). It
 * includes soft deletion support to preserve engagement history without
 * permanently removing records from the database.
 */
abstract class BaseEngagement extends Model
{
    use SoftDeletes;

    /**
     * Get the post associated with this engagement.
     *
     * Defines an inverse one-to-many relationship where a single post
     * can have many engagements, but each engagement belongs to a single post.
     *
     * @return BelongsTo
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the user who performed this engagement.
     *
     * Defines an inverse one-to-many relationship where a single user
     * can perform many engagements, but each engagement belongs to a single user.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
