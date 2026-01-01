<?php

namespace App\Models\Regions;

use App\Models\Pivots\RegionSectionWidget;
use Atannex\Enables\Slugging;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Widget extends Model
{
    use Slugging;
    use SoftDeletes;

    protected string $slugSource = 'name';

    protected $fillable = [
        'slug',
        'name',
        'flag',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Regions that use this widget.
     */
    public function regions(): BelongsToMany
    {
        return $this->belongsToMany(Region::class, 'region_section_widgets')
            ->using(RegionSectionWidget::class)
            ->withPivot(['section_id', 'position', 'config', 'flag', 'metadata']);
    }

    /**
     * Sections this widget is placed in (across regions).
     */
    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(Section::class, 'region_section_widgets')
            ->using(RegionSectionWidget::class)
            ->withPivot(['region_id', 'position', 'config', 'flag', 'metadata']);
    }
}
