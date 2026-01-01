<?php

namespace App\Models\Regions;

use App\Models\Pivots\RegionSectionWidget;
use Atannex\Enables\Scoping;
use Atannex\Enables\Slugging;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
{
    use Scoping;
    use Slugging;
    use SoftDeletes;

    protected string $slugSource = 'name';

    protected $fillable = [
        'name',
        'slug',
        'flag',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Helper attribute for DOM ID when accessed through pivot (e.g., in a region).
     */
    protected function getDomIdAttribute(): string
    {
        return 'section-' . $this->pivot->id;
    }

    /**
     * Regions that use this section.
     */
    public function regions(): BelongsToMany
    {
        return $this->belongsToMany(Region::class, 'region_section_widgets')
            ->using(RegionSectionWidget::class)
            ->withPivot(['widget_id', 'position', 'config', 'flag', 'metadata']);
    }

    /**
     * Widgets placed in this section (across all regions).
     */
    public function widgets(): BelongsToMany
    {
        return $this->belongsToMany(Widget::class, 'region_section_widgets')
            ->using(RegionSectionWidget::class)
            ->withPivot(['region_id', 'position', 'config', 'flag', 'metadata']);
    }
}
