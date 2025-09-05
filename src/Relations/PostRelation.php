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
}
