<?php

namespace App\Models\Pivots;

use App\Enums\Flag;
use App\Models\Regions\Region;
use App\Models\Regions\Section;
use App\Models\Regions\Widget;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegionSectionWidget extends Pivot
{
    use SoftDeletes;

    protected $table = 'region_section_widgets';

    protected $fillable = [
        'region_id',
        'section_id',
        'widget_id',
        'config',
        'flag',
        'metadata',
        'position',
    ];

    protected $casts = [
        'config' => 'array',
        'metadata' => 'array',
        'flag' => Flag::class,
        'position' => 'int',
    ];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function widget(): BelongsTo
    {
        return $this->belongsTo(Widget::class);
    }
}
