<?php

namespace App\Models\Pivots;

use App\Models\Posts\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostEngagement extends Model
{
    protected $primaryKey = 'post_id';
    public $incrementing = false;

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

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
