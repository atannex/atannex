<?php

namespace App\Models\Regions;

use App\Enums\Flag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Morfaw\Supports\EnableSlug;

class Ruler extends Model
{
    use SoftDeletes;
    use EnableSlug;

    protected string $slugSource = 'name';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'image',
        'traditional_title',
        'region_id',
        'rank',
        'reign_start',
        'reign_end',
        'description',
        'flag',
        'metadata',
        'dynasty',
        'phone',
        'email',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'reign_start' => 'date',
        'reign_end' => 'date',
        'metadata' => 'array',
        'flag' => Flag::class,
    ];

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
    public function scopeOfRegion($query, int $regionId)
    {
        return $query->where('region_id', $regionId);
    }

    /**
     * Scope to filter active reigns (no end date).
     */
    public function scopeCurrentlyReigning($query)
    {
        return $query->whereNull('reign_end');
    }

    /**
     * Get full title including traditional title and name.
     */
    public function getFullTitleAttribute(): string
    {
        return trim("{$this->traditional_title} {$this->name}");
    }

    /**
     * Get status flag label (optional if you use enums with labels).
     */
    public function getFlagLabelAttribute(): string
    {
        return $this->flag->label();
    }
}
