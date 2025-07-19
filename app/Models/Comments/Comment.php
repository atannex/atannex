<?php

namespace App\Models\Comments;

use App\Models\User;
use App\Models\Posts\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use SoftDeletes;

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

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function editedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'edited_by');
    }

    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
