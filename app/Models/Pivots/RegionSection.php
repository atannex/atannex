<?php

namespace App\Models\Pivots;

use App\Models\Regions\Section;
use App\Models\Regions\Region;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class RegionSection
 *
 * Pivot model representing the relationship between Regions and Sections.
 * and supports soft deletes.
 *
 * @package App\Models\Pivots
 */
class RegionSection extends Pivot
{
    use SoftDeletes;

    /**
     * The table associated with the pivot model.
     *
     * @var string
     */
    protected $table = 'region_section';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'region_id',
        'section_id',
        'config',
        'position',
        'flag',
        'metadata',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'config' => 'array',
        'metadata' => 'array',
        'position' => 'integer',
    ];

    /**
     * Get the region that this pivot belongs to.
     *
     * @return BelongsTo
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * Get the section that this pivot belongs to.
     *
     * @return BelongsTo
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }
}
