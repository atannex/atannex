<?php

namespace Atannex\Relations;

use App\Models\Tags\Tag;
use App\Models\Regions\Category;
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
 * Defines all Eloquent relationships and slug handling
 * logic for the Post model.
 *
 * Assumes related data is always present — no null checks applied.
 */
trait PostRelation
{
    /**
     * Many-to-Many relationship with Tags.
     *
     * @return BelongsToMany<Tag>
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)
            ->using(PostTag::class)
            ->withTimestamps();
    }

    /**
     * Post belongs to a Category.
     *
     * @return BelongsTo<Category, self>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Post belongs to an Author (Employee).
     *
     * @return BelongsTo<Employee, self>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'author_id');
    }

    /**
     * Post belongs to an Editor (Employee) who last updated it.
     *
     * @return BelongsTo<Employee, self>
     */
    public function editor(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'updated_by');
    }

    /**
     * Many-to-Many relationship with Regions.
     *
     * @return BelongsToMany<Region>
     */
    public function regions(): BelongsToMany
    {
        return $this->belongsToMany(Region::class, 'post_region')
            ->using(PostRegion::class)
            ->withTimestamps();
    }

    /**
     * One-to-One relationship with PostModule.
     *
     * @return HasOne<PostModule>
     */
    public function module(): HasOne
    {
        return $this->hasOne(PostModule::class);
    }

    /**
     * Polymorphic relationship with Comments (only top-level comments).
     *
     * @return MorphMany<Comment>
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
     * Always returns the related category slug path.
     *
     * @return string
     */
    public function getSlugBase(): string
    {
        return $this->category->slug_path;
    }

    /**
     * Get the generated slug for this post.
     *
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * Rebuild this post's slug path.
     *
     * @return void
     */
    public function rebuildSlugPath(): void
    {
        $this->slug_path = $this->buildDynamicSlugPath();
    }

    /**
     * Cascade slug path updates to related tags pivot and self.
     *
     * @return void
     */
    public function cascadeSlugPathUpdates(): void
    {
        $this->rebuildSlugPath();
        $this->saveQuietly();
    }

    /**
     * Clear slug paths for related tags pivots.
     *
     * Placeholder for future logic.
     *
     * @return void
     */
    public function clearRelatedSlugPaths(): void {}
}
