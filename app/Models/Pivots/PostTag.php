<?php

namespace App\Models\Pivots;

use App\Models\Tags\Tag;
use App\Models\Posts\Post;
use App\Models\Traits\Bootable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PostTag extends Pivot
{
    use Bootable;

    protected $table = 'post_tag';

    protected $fillable = [
        'post_id',
        'tag_id',
        'slug_path',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }

}
