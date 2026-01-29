<?php

declare(strict_types=1);

namespace App\Models\Posts;

use App\Contracts\Commentable;
use App\Models\Comments\Comment;
use App\Enums\Flag;
use Atannex\Enables\Slugging;
use App\Models\Regions\Region;
use App\Models\Regions\Category;
use App\Models\Regions\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Video extends Model implements Commentable
{
    use SoftDeletes;
    use Slugging;

    /**
     * Slug source field.
     */
    protected string $slugSource = 'title';

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

    protected $casts = [
        'published_at' => 'datetime',
        'flag'         => Flag::class,
        'duration'     => 'integer',
    ];

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
     * Get all reviews for this post.
     */
    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    /**
     * Get only approved reviews.
     */
    public function approvedReviews(): MorphMany
    {
        return $this->reviews()->where('flag', Flag::APPROVED);
    }

    /**
     * Get only pending reviews.
     */
    public function pendingReviews(): MorphMany
    {
        return $this->reviews()->where('flag', Flag::PENDING_REVIEW);
    }

    /**
     * Average rating for the video.
     */
    public function averageRating(): float
    {
        return (float) $this->approvedReviews()->avg('reviewer_rating') ?? 0;
    }

    /**
     * Total number of approved reviews.
     */
    public function reviewCount(): int
    {
        return $this->approvedReviews()->count();
    }

    /**
     * The category this video belongs to
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * The employee who created / owns this video
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'author_id');
    }

    /**
     * The region this video is targeted to / associated with
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function module(): HasOne
    {
        return $this->hasOne(VideoModule::class);
    }

    /**
     * The employee who last updated this video (nullable)
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'updated_by');
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->where('flag', Flag::PUBLISHED);
    }

    public function scopeDrafts($query)
    {
        return $query->where('flag', Flag::DRAFT);
    }

    public function isPublished(): bool
    {
        return $this->published_at !== null
            && $this->published_at->isPast()
            && $this->flag === Flag::PUBLISHED;
    }

    public function getDurationForHumansAttribute(): ?string
    {
        if (!$this->duration) {
            return null;
        }

        $minutes = floor($this->duration / 60);
        $seconds = $this->duration % 60;

        return sprintf('%02d:%02d', $minutes, $seconds);
    }
}
