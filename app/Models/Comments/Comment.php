<?php

namespace App\Models\Comments;

use App\Models\Comments\Traits\GeoIP;
use App\Models\Comments\Traits\Scoping;
use Illuminate\Database\Eloquent\Model;
use Ngangagah\Relations\CommentRelation;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Comment
 *
 * Represents a comment in the system, supporting hierarchical comments, geolocation,
 * soft deletion, and moderation features.
 */
class Comment extends Model
{
    use SoftDeletes;
    use GeoIP;
    use CommentRelation;
    use Scoping;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'comments';

    /**
     * The relationships to always eager-load.
     *
     * @var array
     */
    protected $with = ['user'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'post_id',
        'parent_id',
        'comment',
        'status',
        'user_id',
        'ip_address',
        'ip_country',
        'user_agent',
        'likes_count',
        'dislikes_count',
        'replies_count',
        'edited_at',
        'edited_reason',
        'edited_by',
        'deleted_by',
        'reviewed_at',
        'reviewed_by',
        'moderation_notes',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'edited_at'        => 'datetime',
        'reviewed_at'      => 'datetime',
        'moderation_notes' => 'array',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'likes_count'    => 0,
        'dislikes_count' => 0,
        'replies_count'  => 0,
        'status'         => 'pending',
    ];
}
