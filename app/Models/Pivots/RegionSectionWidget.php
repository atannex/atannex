<?php

namespace App\Models\Pivots;

use App\Enums\Flag;
use App\Models\Regions\Region;
use App\Models\Regions\Section;
use App\Models\Regions\Widget;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * RegionSectionWidget Pivot Model
 *
 * Represents the placement of a Widget inside a Section
 * for a specific Region, including configuration and state.
 */
class RegionSectionWidget extends Pivot
{
    use SoftDeletes;

    /**
     * The table associated with the pivot model.
     */
    protected $table = 'region_section_widgets';

    /**
     * Attributes that are mass assignable.
     */
    protected $fillable = [
        'region_id',
        'section_id',
        'widget_id',
        'config',
        'flag',
        'metadata',
        'position',
    ];

    /**
     * Attribute casting for type safety.
     */
    protected $casts = [
        'config' => 'array',
        'metadata' => 'array',
        'flag' => Flag::class,
        'position' => 'int',
    ];

    /**
     * Get the region associated with this pivot.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo The region relationship.
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

    /**
     * Get the widget associated with this pivot.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo The BelongsTo relationship to the Widget model.
     */
    public function widget(): BelongsTo
    {
        return $this->belongsTo(Widget::class);
    }
}
