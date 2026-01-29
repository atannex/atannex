<?php

declare(strict_types=1);

namespace App\Models\Posts;

use App\Contracts\Commentable;
use App\Enums\Flag;
use App\Models\Comments\Comment;
use App\Models\Regions\Category;
use App\Models\Regions\Employee;
use App\Models\Regions\Region;
use Atannex\Enables\Slugging;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video extends Model implements Commentable
{
    use SoftDeletes;
    use Slugging;

    /**
     * The attribute used to generate the slug.
     */
    protected string $slugSource = 'title';

    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        'title',
        'description',
        'video_url',
        'image',
        'duration',
        'flag',
        'published_at',
        'category_id',
        'author_id',
        'region_id',
        'updated_by',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'published_at' => 'datetime',
        'duration'     => 'integer',
        'flag'         => Flag::class,
    ];

    /* -----------------------------------------------------------------
     | Relationships
     | -----------------------------------------------------------------
     */

    /**
     * Top-level polymorphic comments.
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')
            ->whereNull('parent_id')
            ->latest();
    }

    /**
     * Polymorphic reviews.
     */
    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    /**
     * Approved reviews only.
     */
    public function approvedReviews(): MorphMany
    {
        return $this->reviews()->where('flag', Flag::APPROVED);
    }

    /**
     * Pending reviews only.
     */
    public function pendingReviews(): MorphMany
    {
        return $this->reviews()->where('flag', Flag::PENDING_REVIEW);
    }

    /**
     * Video category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Video author.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'author_id');
    }

    /**
     * Associated region.
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * Optional learning/module mapping.
     */
    public function module(): HasOne
    {
        return $this->hasOne(VideoModule::class);
    }

    /**
     * Last editor of the video.
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'updated_by');
    }

    /* -----------------------------------------------------------------
     | Scopes
     | -----------------------------------------------------------------
     */

    /**
     * Published videos only.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->where('flag', Flag::PUBLISHED);
    }

    /**
     * Draft videos only.
     */
    public function scopeDrafts(Builder $query): Builder
    {
        return $query->where('flag', Flag::DRAFT);
    }

    /* -----------------------------------------------------------------
     | Aggregates & State
     | -----------------------------------------------------------------
     */

    /**
     * Average rating from approved reviews.
     */
    public function averageRating(): float
    {
        return (float) ($this->approvedReviews()->avg('reviewer_rating') ?? 0.0);
    }

    /**
     * Total approved review count.
     */
    public function reviewCount(): int
    {
        return $this->approvedReviews()->count();
    }

    /**
     * Determine if the video is publicly published.
     */
    public function isPublished(): bool
    {
        return $this->published_at !== null
            && $this->published_at->isPast()
            && $this->flag === Flag::PUBLISHED;
    }

    /* -----------------------------------------------------------------
     | Accessors
     | -----------------------------------------------------------------
     */

    /**
     * Human-readable duration (MM:SS).
     */
    public function getDurationForHumansAttribute(): ?string
    {
        if ($this->duration === null || $this->duration <= 0) {
            return null;
        }

        return sprintf(
            '%02d:%02d',
            intdiv($this->duration, 60),
            $this->duration % 60
        );
    }
}
