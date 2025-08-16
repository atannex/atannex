<?php

namespace App\Models\Pivots;

/**
 * Class PostLike
 *
 * Represents a like on a post by a user.
 * Uses BatchedEngagement trait to queue engagement updates asynchronously.
 */
class PostLike extends BaseEngagement
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'post_id',
        'user_id',
        'liked_at',
    ];

    /**
     * The attributes that should be cast to dates.
     *
     * @var array<int, string>
     */
    protected $dates = ['liked_at'];

}
