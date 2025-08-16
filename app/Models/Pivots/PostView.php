<?php

namespace App\Models\Pivots;

/**
 * Class PostView
 *
 * Represents a view of a post by a user or guest.
 * Uses BatchedEngagement trait to queue engagement updates asynchronously.
 */
class PostView extends BaseEngagement
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'post_id',
        'user_id',
        'ip_address',
        'user_agent',
        'session_id',
        'referer_url',
    ];
}
