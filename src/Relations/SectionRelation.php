<?php

namespace Atannex\Relations;

use App\Models\Pivots\CategorySection;
use App\Models\Pivots\RegionSectionWidget;
use App\Models\Regions\Category;
use App\Models\Regions\Region;
use App\Models\Regions\Widget;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait SectionRelation
{
    public function region(): BelongsToMany
    {
        return $this->belongsToMany(Region::class, 'region_section_widgets')
            ->using(RegionSectionWidget::class)
            ->withPivot(['widget_id', 'config', 'flag', 'metadata']);
    }

    public function widgets(): BelongsToMany
    {
        return $this->belongsToMany(Widget::class, 'region_section_widgets')
            ->using(RegionSectionWidget::class)
            ->withPivot(['region_id', 'config', 'flag', 'metadata']);
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
