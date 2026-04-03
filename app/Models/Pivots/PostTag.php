<?php

namespace App\Models\Pivots;

use App\Models\Posts\Post;
use App\Models\Tags\Tag;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PostTag extends Pivot
{
    protected $table = 'post_tag';

    protected $fillable = [
        'post_id',
        'tag_id',
    ];

    protected $casts = [
        'post_id' => 'int',
        'tag_id' => 'int',
    ];

    public $timestamps = true;

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }
}
