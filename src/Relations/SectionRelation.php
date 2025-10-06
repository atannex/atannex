<?php

namespace Atannex\Relations;

use App\Models\Regions\Widget;
use App\Models\Regions\Category;
use App\Models\Regions\Region;
use App\Models\Pivots\RegionSection;
use App\Models\Pivots\WidgetSection;
use App\Models\Pivots\CategorySection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait SectionRelation
{
    /**
     * Regions attached via region_section pivot.
     */
    public function regions(): BelongsToMany
    {
        return $this->belongsToMany(Region::class, 'region_section')
            ->using(RegionSection::class)
            ->withPivot(['config', 'position', 'flag', 'metadata'])
            ->orderByPivot('position')
            ->withTimestamps();
    }

    /**
     * Widgets attached via widget_section pivot.
     */
    public function widgets(): BelongsToMany
    {
        return $this->belongsToMany(Widget::class, 'widget_section')
            ->using(WidgetSection::class)
            ->withPivot(['config', 'position', 'flag', 'metadata'])
            ->orderByPivot('position')
            ->withTimestamps();
    }

    /**
     * Categories attached via category_section pivot.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_section')
            ->using(CategorySection::class)
            ->withPivot(['config', 'flag', 'metadata'])
            ->withTimestamps();
    }
}
