<?php

namespace App\Models\Pivots;

use App\Models\Tags\Tag;
use App\Models\Posts\Post;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PostTag extends Pivot
{
    protected $table = 'post_tag';

    protected $fillable = ['post_id', 'tag_id'];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }
}
