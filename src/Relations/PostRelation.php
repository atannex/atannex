<?php

namespace Atannex\Relations;

use App\Models\Tags\Tag;
use App\Models\Pages\Category;
use App\Models\Pivots\PostTag;
use App\Models\Regions\Region;
use App\Models\Comments\Comment;
use App\Models\Regions\Employee;
use App\Models\Pivots\PostRegion;
use App\Models\Modules\PostModule;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Trait PostRelation
 *
 * Defines all Eloquent relationships for the Post model.
 */
trait PostRelation
{
    /**
     * Many-to-Many relationship with Tags.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)
            ->using(PostTag::class)
            ->withTimestamps()
            ->withPivot('slug_path');
    }

    /**
     * Post belongs to a Category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Post belongs to an Author (Employee).
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'author_id');
    }

    /**
     * Post belongs to an Editor (Employee) who last updated it.
     */
    public function editor(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'updated_by');
    }

    /**
     * Many-to-Many relationship with Regions.
     */
    public function regions(): BelongsToMany
    {
        return $this->belongsToMany(Region::class, 'post_region')
            ->using(PostRegion::class)
            ->withTimestamps();
    }

    /**
     * One-to-One relationship with PostModule.
     */
    public function module(): HasOne
    {
        return $this->hasOne(PostModule::class);
    }

    /**
     * Polymorphic relationship with Comments (only top-level comments).
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')
            ->whereNull('parent_id')
            ->latest();
    }

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
