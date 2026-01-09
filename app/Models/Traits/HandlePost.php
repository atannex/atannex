<?php

namespace App\Models\Traits;

use App\Models\Comments\Comment;
use Illuminate\Support\Facades\Auth;
use App\Models\Modules\PostModule;
use App\Models\Pivots\PostRegion;
use App\Models\Pivots\PostTag;
use App\Models\Posts\Post;
use App\Models\Regions\Category;
use App\Models\Regions\Employee;
use App\Models\Regions\Region;
use App\Models\Tags\Tag;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Trait PostRelation
 *
 * Defines all Eloquent relationships and slug logic for the Post model.
 */
trait HandlePost
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
     * Belongs to an Editor (Employee) — updated_by.
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
            ->withPivot(['region_id', 'post_id'])
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
     * Belongs to the employee who last updated the post.
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'updated_by')->withTrashed();
    }

    /**
     * Check if user is authenticated.
     */
    protected function isUserAuthenticated(): bool
    {
        return Auth::check();
    }

    /**
     * Recalculates the slug_path based on category and slug.
     */
    public function refreshSlugPath(): void
    {
        $category = $this->category()
            ->withoutGlobalScopes()
            ->select(['id', 'slug_path'])
            ->first();

        $this->slug_path = trim(
            ($category->slug_path ?? '') . '/' . $this->slug,
            '/'
        );
    }

    /**
     * Automatically refresh slug_path when slug or category changes.
     */
    public function setSlugAttribute($value): void
    {
        $this->attributes['slug'] = $value;

        if (isset($this->attributes['category_id'])) {
            $this->refreshSlugPath();
        }
    }

    /**
     * Boot the model and attach saving events for category changes and updated_by.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::saving(function (Post $post) {
            // Refresh slug path if category changed
            if ($post->isDirty('category_id')) {
                $post->refreshSlugPath();
            }

            // Set updated_by to current user's employee ID
            if (Auth::check() && Auth::user()->employee) {
                $post->updated_by = Auth::user()->employee->id;
            }
        });
    }
}
