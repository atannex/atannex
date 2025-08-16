<?php

namespace App\Models\Pivots;

/**
 * Class PostRating
 *
 * Represents a rating (and optional review) given by a user on a post.
 * Uses BatchedEngagement trait to queue engagement updates asynchronously.
 */
class PostRating extends BaseEngagement
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'post_id',
        'user_id',
        'rating',
        'review',
        'rating_source',
    ];
}
