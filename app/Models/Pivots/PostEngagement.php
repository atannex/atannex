<?php

namespace App\Models\Pivots;

use App\Models\Posts\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class PostEngagement
 *
 * Represents aggregated engagement metrics for a Post.
 */
class PostEngagement extends Model
{
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'post_id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'post_id',
        'total_views',
        'total_likes',
        'total_shares',
        'total_comments',
        'avg_rating',
        'engagement_score',
        'last_engagement_at',
    ];

    /**
     * The attributes that should be cast to dates.
     *
     * @var array<int, string>
     */
    protected $dates = ['last_engagement_at'];

    /**
     * Get the post that owns this engagement.
     *
     * @return BelongsTo
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
