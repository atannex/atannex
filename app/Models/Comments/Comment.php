<?php

namespace App\Models\Comments;

use App\Models\Comments\Traits\GeoIP;
use App\Models\Comments\Traits\Scoping;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ngangagah\Relations\CommentRelation;

class Comment extends Model
{
    use SoftDeletes;
    use GeoIP;
    use CommentRelation;
    use Scoping;

    protected $with = ['user'];

    protected $table = 'comments';

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

    protected $casts = [
        'edited_at'        => 'datetime',
        'reviewed_at'      => 'datetime',
        'moderation_notes' => 'array',
    ];

    protected $attributes = [
        'likes_count'    => 0,
        'dislikes_count' => 0,
        'replies_count'  => 0,
        'status'         => 'pending',
    ];
}
