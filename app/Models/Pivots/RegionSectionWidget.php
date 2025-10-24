<?php

namespace App\Models\Pivots;

use App\Enums\Flag;
use App\Models\Regions\Region;
use App\Models\Regions\Widget;
use App\Models\Regions\Section;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class RegionSectionWidget extends Pivot
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'region_section_widgets';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
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
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'config' => 'array',
        'metadata' => 'array',
        'flag' => Flag::class
    ];

    /**
     * -------------------------
     *  RELATIONSHIPS
     * -------------------------
     */

    /**
     * Get the region associated with this widget.
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * Get the section associated with this widget.
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * Get the widget configuration record.
     */
    public function widget(): BelongsTo
    {
        return $this->belongsTo(Widget::class);
    }
}
