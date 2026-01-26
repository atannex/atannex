<?php

declare(strict_types=1);

namespace App\Models\Posts;

use App\Enums\Flag;
use App\Models\Regions\Region;
use App\Models\Regions\Category;
use App\Models\Regions\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video extends Model
{
    use SoftDeletes;

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
