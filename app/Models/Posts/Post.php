<?php

namespace App\Models\Posts;

use App\Enums\Flag;
use App\Models\Tags\Tag;
use App\Contracts\Sluggable;
use App\Contracts\Commentable;
use Atannex\Traits\Bootable;
<<<<<<< HEAD
use Atannex\Enables\EnableSlug;
use Atannex\Enables\EnableScope;
=======
use Atannex\Enables\Slug;
use Atannex\Enables\Scope;
>>>>>>> b90bee7 (SEO for news → news_keywords, article:section, published_time improve indexing by Google News.)
use Atannex\Traits\Cleaning;
use Atannex\Relations\PostRelation;
use App\Livewire\Interactions\HasLikes;
use App\Livewire\Interactions\HasViews;
use App\Livewire\Interactions\HasShares;
use App\Livewire\Interactions\HasRatings;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Post
 *
 * Represents a post with hierarchical slug management,
 * commenting, and interaction features.
 */
class Post extends Model implements Commentable, Sluggable
{
    use SoftDeletes;
<<<<<<< HEAD
    use EnableSlug;
    use EnableScope;
=======
    use Slug;
    use Scope;
>>>>>>> b90bee7 (SEO for news → news_keywords, article:section, published_time improve indexing by Google News.)
    use PostRelation;
    use Cleaning;
    use Bootable;
    use HasLikes;
    use HasRatings;
    use HasShares;
    use HasViews;

    /**
     * Attributes that store image paths.
     *
     * @return array<string>
     */
    protected function imageAttributes(): array
    {
        return ['image'];
    }

    /**
     * Storage disk for image cleanup.
     *
     * @return string
     */
    protected function imageDisk(): string
    {
        return 'public';
    }

    /**
     * Source attribute for slug generation.
     *
     * @var string
     */
    protected string $slugSource = 'title';

    /**
     * Mass assignable attributes.
     *
     * @var array<string>
     */
    protected $fillable = [
        'title',
        'slug',
        'slug_path',
        'date_path',
        'flag',
        'category_id',
        'author_id',
        'updated_by',
        'description',
        'image',
        'published_at',
    ];

    /**
     * Attribute casting.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'published_at' => 'datetime',
        'flag' => Flag::class,
    ];

    /**
     * Model default attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'flag' => Flag::DRAFT,
    ];

    /**
     * ---------------------------
     * Sluggable Implementation
     * ---------------------------
     */

    /**
     * Get the base string for slug generation.
     *
     * @return string|null The category's slug path.
     */
    public function getSlugBase(): ?string
    {
        return $this->category?->slug_path;
    }

    /**
     * Get the generated slug for this post.
     *
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * Rebuild this post's slug path.
     */
    public function rebuildSlugPath(): void
    {
        $this->slug_path = $this->buildDynamicSlugPath();
    }

    /**
     * Cascade slug path updates to related tags pivot and self.
     */
    public function cascadeSlugPathUpdates(): void
    {
        // Update the post's own slug path
        $this->rebuildSlugPath();
        $this->saveQuietly();

        // Update related tags' pivot slug paths
        $categorySlug = $this->category?->slug_path;
        if ($categorySlug) {
            $this->tags()->get()->each(function (Tag $tag) use ($categorySlug) {
                $tag->pivot?->forceFill([
                    'slug_path' => $this->buildSlugPath($categorySlug, $tag->slug),
                ])->saveQuietly();
            });
        }
    }

    /**
     * Clear slug paths for related tags pivots.
     */
    public function clearRelatedSlugPaths(): void
    {
        $this->tags()->newPivotQuery()->update(['slug_path' => null]);
    }
}
