<?php

namespace Atannex\Relations;

use App\Models\Tags\Tag;
use App\Models\Regions\Category;
use App\Models\Regions\Region;
use App\Models\Comments\Comment;
use App\Models\Regions\Employee;
use App\Models\Modules\PostModule;
use App\Models\Pivots\PostTag;
use App\Models\Pivots\PostRegion;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Trait PostRelation
 *
 * Defines all Eloquent relationships and slug handling
 * logic for the Post model.
 */
trait PostRelation
{
    /**
     * Many-to-Many relationship with Tags.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tag')
            ->using(PostTag::class)
            ->withTimestamps();
    }

    /**
     * Belongs to a Category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Belongs to an Author (Employee).
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'author_id');
    }

    /**
     * Belongs to an Editor (Employee).
     */
    public function editor(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'updated_by');
    }

    /**
     * Many-to-Many relationship with Regions.
     *
     * Filtering (SoftDeletes + flag) handled inside PostRegion pivot.
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
     * Polymorphic relationship with Comments (only top-level).
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
     * Base string for slug generation (category path).
     */
    public function getSlugBase(): string
    {
        // Avoid lazy-loading errors
        return $this->category?->slug_path ?? '';
    }

    /**
     * Generated slug for this post.
     */
    public function getSlug(): string
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
     * Cascade slug path updates to related pivots and self.
     */
    public function cascadeSlugPathUpdates(): void
    {
        $this->rebuildSlugPath();
        $this->saveQuietly();

        // Placeholder for pivot propagation
        $this->updatePivotSlugs();
    }

    /**
     * Clear slug paths for related pivots.
     *
     * Future extension point.
     */
    public function clearRelatedSlugPaths(): void {}

    /**
     * Update pivot slug paths (tags, regions).
     *
     * @return void
     */
    protected function updatePivotSlugs(): void
    {
        // Example: update tag pivot slugs if schema supports it
        // $this->tags()->each(fn ($tag) => $tag->pivot->updateQuietly([...]));
    }
}
