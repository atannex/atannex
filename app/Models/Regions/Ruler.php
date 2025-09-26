<?php

namespace App\Models\Regions;

use Atannex\Enables\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ruler extends Model
{
    use SoftDeletes;
    use HasSlug;

    protected string $slugSource = 'name';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'slug',
        'image',
        'dynasty',
        'title',
        'classification',
        'reign_start',
        'reign_end',
        'region_id',
        'phone',
        'email',
        'description',
        'metadata',
        'flag',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'reign_start' => 'date',
        'reign_end' => 'date',
        'metadata' => 'array',
    ];

    /**
     * The attributes that should be mutated to dates.
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Scope: Filter by classification
     */
    protected function scopeClassification($query, $classification)
    {
        return $query->where('classification', $classification);
    }

    /**
     * Get the region associated with the Fon.
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * Scope for filtering by region.
     */
    protected function scopeOfRegion($query, int $regionId)
    {
        return $query->where('region_id', $regionId);
    }

    /**
     * Scope to filter active reigns (no end date).
     */
    protected function scopeCurrentlyReigning($query)
    {
        return $query->whereNull('reign_end');
    }

    /**
     * Get full title including traditional title and name.
     */
    protected function getFullTitleAttribute(): string
    {
        return trim(sprintf('%s %s', $this->title, $this->name));
    }
}
