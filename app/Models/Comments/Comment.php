<?php

namespace App\Models\Comments;

use App\Models\Traits\HandleComment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;
    use HandleComment;

    protected $fillable = [
        'user_id',
        'is_guest',
        'guest_name',
        'guest_email',
        'commentable_type',
        'commentable_id',
        'parent_id',
        'reply_count',
        'comment',
        'ip_address',
        'comment_hash',
        'edited_at',
        'like_count',
        'dislike_count',
    ];

    protected $casts = [
        'is_guest'      => 'boolean',
        'edited_at'     => 'datetime',
        'reply_count'   => 'integer',
        'like_count'    => 'integer',
        'dislike_count' => 'integer',
    ];
}
