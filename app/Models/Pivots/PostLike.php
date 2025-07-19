<?php

namespace App\Models\Pivots;

use App\Models\User;
use App\Models\Posts\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostLike extends Model
{
    use SoftDeletes;

    protected $fillable = ['post_id', 'user_id', 'liked_at'];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
