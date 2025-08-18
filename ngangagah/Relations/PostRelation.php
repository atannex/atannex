<?php

namespace Ngangagah\Relations;

use App\Models\User;
use App\Models\Tags\Tag;
use App\Models\Pages\Category;
use App\Models\Pivots\PostTag;
use App\Models\Regions\Region;
use App\Models\Pivots\PostLike;
use App\Models\Pivots\PostView;
use App\Models\Comments\Comment;
use App\Models\Pivots\PostShare;
use App\Models\Regions\Employee;
use App\Models\Pivots\PostRating;
use App\Models\Pivots\PostRegion;
use App\Models\Modules\PostModule;
use App\Models\Pivots\PostEngagement;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
     *
     * @return BelongsToMany
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
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Post belongs to an Author (Employee).
     *
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'author_id');
    }

    /**
     * Post belongs to an Editor (Employee) who last updated it.
     *
     * @return BelongsTo
     */
    public function editor(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'updated_by');
    }

    /**
     * Many-to-Many relationship with Regions.
     *
     * @return BelongsToMany
     */
    public function regions(): BelongsToMany
    {
        return $this->belongsToMany(Region::class, 'post_region')
            ->using(PostRegion::class)
            ->withTimestamps()
            ->withPivot('deleted_at');
    }

    /**
     * One-to-One relationship with PostModule.
     *
     * @return HasOne
     */
    public function module(): HasOne
    {
        return $this->hasOne(PostModule::class);
    }

    /**
     * Polymorphic relationship with Comments (only top-level comments).
     *
     * @return MorphMany
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')
            ->whereNull('parent_id')
            ->latest();
    }

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'post_likes')
            ->using(PostLike::class)
            ->withPivot('liked_at', 'deleted_at')
            ->withTimestamps();
    }

    public function isLikedBy(User $user)
    {
        return $this->likes()->wherePivot('user_id', $user->id)
            ->whereNull('post_likes.deleted_at')
            ->exists();
    }

    public function likesCount()
    {
        return $this->likes()
            ->whereNull('post_likes.deleted_at')
            ->count();
    }

    /**
     * One-to-Many relationship with PostRatings.
     *
     * @return HasMany
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(PostRating::class);
    }

    /**
     * One-to-Many relationship with PostShares.
     *
     * @return HasMany
     */
    public function shares(): HasMany
    {
        return $this->hasMany(PostShare::class);
    }

    /**
     * One-to-Many relationship with PostViews.
     *
     * @return HasMany
     */
    public function views(): HasMany
    {
        return $this->hasMany(PostView::class);
    }

    /**
     * One-to-One relationship with PostEngagement.
     *
     * @return HasOne
     */
    public function engagement(): HasOne
    {
        return $this->hasOne(PostEngagement::class);
    }
}
