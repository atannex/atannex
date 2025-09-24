<?php

namespace Atannex\Relations;

use App\Models\Regions\Widget;
use App\Models\Regions\Category;
use App\Models\Pivots\RegionSection;
use App\Models\Pivots\WidgetSection;
use App\Models\Pivots\CategorySection;
use App\Models\Regions\Region;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait SectionRelation
{
    /**
     * Defines the relationship between this model and regions via the pivot table `region_section`.
     * Includes pivot fields like config, position, flags, and timestamps.
     * Orders results by the pivot's `position` column.
     */
    public function regions(): BelongsToMany
    {
        return $this->belongsToMany(Region::class, 'region_section')
            ->using(RegionSection::class)
            ->withPivot([
                'config',
                'position',
                'flag',
                'metadata',
                'created_at',
                'updated_at',
                'deleted_at',
            ])
            ->withTimestamps()
            ->wherePivot('deleted_at')
            ->orderByPivot('position');
    }

    /**
     * Defines the relationship between this model and widgets via the pivot table `widget_section`.
     * Similar to regions, includes `position` for ordering.
     */
    public function widgets(): BelongsToMany
    {
        return $this->belongsToMany(Widget::class, 'widget_section')
            ->using(WidgetSection::class)
            ->withPivot([
                'config',
                'position',
                'flag',
                'metadata',
                'created_at',
                'updated_at',
                'deleted_at',
            ])
            ->withTimestamps()
            ->wherePivot('deleted_at')
            ->orderByPivot('position');
    }

    /**
     * Defines the relationship between this model and categories via the pivot table `category_section`.
     * This pivot table does NOT have a `position` column, so we avoid ordering by it.
     * Only includes relevant pivot fields.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_section')
            ->using(CategorySection::class)
            ->withPivot([
                'config',
                'flag',
                'metadata',
            ])
            ->withTimestamps();
    }
}
