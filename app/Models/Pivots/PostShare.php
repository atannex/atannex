<?php

namespace App\Models\Pivots;

/**
 * Class PostShare
 *
 * Represents a share of a post by a user on a platform.
 * Uses BatchedEngagement trait to queue engagement updates asynchronously.
 */
class PostShare extends BaseEngagement
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'post_id',
        'user_id',
        'platform',
        'share_url',
        'share_count',
        'shared_at',
    ];

    /**
     * The attributes that should be cast to dates.
     *
     * @var array<int, string>
     */
    protected $dates = ['shared_at'];
}
