<?php

namespace App\Models\Pages;

use App\Models\Posts\Post;
use App\Contracts\Sluggable;
use Atannex\Traits\Bootable;
use Atannex\Traits\Resolver;
use Atannex\Enables\EnableSlug;
use Atannex\Enables\EnableScope;
use Atannex\Filters\GetHierarchy;
use Atannex\Relations\CategoryRelation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Category
 *
 * Represents a category model with slug management and hierarchical relationships.
 */
class Category extends Model implements Sluggable
{
    use SoftDeletes;
    use EnableSlug;
    use CategoryRelation;
    use EnableScope;
    use GetHierarchy;
    use Resolver;
    use Bootable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'flag',
        'image',
        'description',
        'published_at',
        'parent_id',
        'slug_path',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * The source attribute for slug generation.
     *
     * @var string
     */
    protected string $slugSource = 'name';

    /**
     * Get the base string for slug generation.
     *
     * @return string|null The parent category's slug path or null if no parent exists.
     */
    public function getSlugBase(): ?string
    {
        return $this->parent?->slug_path;
    }

    /**
     * Get the generated slug for the model.
     *
     * @return string|null The category's slug.
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * Update slug paths for related posts.
     */
    public function cascadeSlugPathUpdates(): void
    {
        $this->posts()->with('tags.tag')->get()->each(function (Post $post) {
            $post->updateSlugPath()->saveQuietly();
        });
    }

    /**
     * Clear slug paths for related posts.
     */
    public function clearRelatedSlugPaths(): void
    {
        $this->posts()->get()->each(function (Post $post) {
            $post->slug_path = null;
            $post->saveQuietly();
        });
    }
}
