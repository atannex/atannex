<?php

declare(strict_types=1);

namespace App\Models\Posts;

use App\Models\Tags\Tag;
use Illuminate\Support\Str;
use Atannex\Enables\Scoping;
use Atannex\Enables\Slugging;
use App\Models\Traits\HasComments;
use App\Models\Pivots\PostTag;
use App\Models\Regions\Region;
use App\Models\Comments\Comment;
use App\Models\Regions\Category;
use App\Models\Regions\Employee;
use App\Models\Traits\HandlePost;
use App\Models\Traits\HasRatings;
use App\Models\Modules\PostModule;
use App\Models\Traits\HasReaction;
use App\Models\Traits\HasShares;
use App\Models\Traits\HasViews;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    use HandlePost;
    use Scoping;
    use Slugging;
    use SoftDeletes;
    use HasRatings;
    use HasReaction;
    use HasViews;
    use HasComments;
    use HasShares;

    /**
     * Slug source field.
     */
    protected string $slugSource = 'title';

    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        'title',
        'slug',
        'category_id',
        'region_id',
        'author_id',
        'updated_by',
        'description',
        'image',
        'published_at',
        'slug_path',

        'is_breaking',
        'breaking_at',
        'breaking_expires',

        'is_editor_pick',
        'editor_pick_at',
        'editor_pick_expires',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'published_at'        => 'datetime',
        'is_breaking'         => 'boolean',
        'breaking_at'         => 'datetime',
        'breaking_expires'    => 'datetime',

        'is_editor_pick'      => 'boolean',
        'editor_pick_at'      => 'datetime',
        'editor_pick_expires' => 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
    /**
     * A post can have many videos.
     */
    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }

    /**
     * Get all reviews for this post.
     */
    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

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

    /*
    |--------------------------------------------------------------------------
    | Visitor Identity
    |--------------------------------------------------------------------------
    */

    protected function resolveVisitorKey(): string
    {
        if (Auth::check()) {
            return 'user_' . Auth::id();
        }

        $visitorId = request()->cookie('visitor_id');

        if (! $visitorId) {
            $visitorId = (string) Str::uuid();

            cookie()->queue(
                cookie('visitor_id', $visitorId, 60 * 24 * 365)
            );
        }

        return 'guest_' . $visitorId;
    }
}
