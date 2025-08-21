<?php

namespace App\Models\Pivots;

use App\Contracts\Sluggable;
use App\Models\Tags\Tag;
use App\Models\Posts\Post;
use App\Models\Traits\Bootable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PostTag extends Pivot implements Sluggable
{
    use Bootable;

    protected $table = 'post_tag';

    protected $fillable = [
        'post_id',
        'tag_id',
        'slug_path',
    ];

    protected $casts = [
        'post_id' => 'integer',
        'tag_id' => 'integer',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class, 'tag_id');
    }

    public function getSlugBase(): string
    {
        return $this->post->category->slug_path;
    }

    public function getSlug(): string
    {
        return $this->tag->slug;
    }

    public function cascadeSlugPathUpdates(): void
    {
        // Likely no cascade needed for tags
    }

    public function clearRelatedSlugPaths(): void
    {
        // Likewise, probably nothing to clear
    }
}
