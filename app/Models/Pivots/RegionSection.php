<?php

namespace App\Models\Pivots;

use App\Enums\Flag;
use App\Models\Regions\Section;
use App\Models\Regions\Region;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class RegionSection
 *
 * Pivot model representing the relationship between Regions and Sections.
 * Supports soft deletes and enforces published filtering.
 */
class RegionSection extends Pivot
{
    use SoftDeletes;

    protected $table = 'region_section';

    protected $fillable = [
        'region_id',
        'section_id',
        'config',
        'position',
        'flag',
        'metadata',
    ];

    protected $casts = [
        'config'   => 'array',
        'metadata' => 'array',
        'position' => 'integer',
    ];

    /**
     * Boot the model and apply global scopes.
     */
    protected static function booted(): void
    {
        static::addGlobalScope('published', function (Builder $builder) {
            $builder->where('flag', Flag::PUBLISHED);
        });
    }

    /**
     * Scope for only published pivot entries.
     */
    protected function scopePublished(Builder $query): Builder
    {
        return $query->where('flag', Flag::PUBLISHED);
    }

    /**
     * Region relationship.
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * Section relationship.
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }
}
