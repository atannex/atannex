<?php

namespace App\Models\Pivots;

use App\Contracts\Sluggable;
use App\Models\Posts\Post;
use App\Models\Tags\Tag;
use Atannex\Traits\Bootable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class PostTag
 *
 * Represents a pivot model for post-tag relationships with slug management.
 */
class PostTag extends Pivot implements Sluggable
{
    use Bootable;

    /**
     * The table associated with the pivot model.
     *
     * @var string
     */
    protected $table = 'post_tag';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'post_id',
        'tag_id',
        'slug_path',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'post_id' => 'integer',
        'tag_id' => 'integer',
    ];

    /**
     * Get the post that belongs to this pivot.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the tag that belongs to this pivot.
     */
    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }

    /**
     * Get the base string for slug generation.
     *
     * @return string|null The post's category slug path or null if not available.
     */
    public function getSlugBase(): ?string
    {
        return $this->post?->category?->slug_path;
    }

    /**
     * Get the generated slug for the pivot.
     *
     * @return string|null The tag's slug or null if not available.
     */
    public function getSlug(): ?string
    {
        return $this->tag?->slug;
    }

    /**
     * Update slug paths for related entities.
     */
    public function cascadeSlugPathUpdates(): void
    {
        // No cascading updates required for pivot.
    }

    /**
     * Clear slug paths for related entities.
     */
    public function clearRelatedSlugPaths(): void
    {
        // No related slug paths to clear for pivot.
    }
}
