<?php

namespace Atannex\Relations;

use App\Enums\Flag;
use App\Models\Posts\Post;
use App\Models\Regions\Ruler;
use App\Models\Regions\Region;
use App\Models\Regions\Widget;
use App\Models\Regions\Section;
use App\Models\Pivots\PostRegion;
use App\Models\Pivots\RegionSectionWidget;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait RegionRelation
{
    /**
     * Get the parent region.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'parent_id');
    }

    /**
     * Get published child regions.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Region::class, 'parent_id')
            ->where('flag', Flag::PUBLISHED);
    }

    /**
     * Get child regions recursively (with nested published children).
     */
    public function childrenRecursive(): HasMany
    {
        return $this->children()->with('childrenRecursive');
    }

    /**
     * Get the ruler of this region.
     */
    public function ruler(): HasOne
    {
        return $this->hasOne(Ruler::class);
    }

    /**
     * Get published posts associated with this region.
     */
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class)
            ->using(PostRegion::class)
            ->withTimestamps();
    }

    /**
     * Get published sections in this region, ordered by position.
     */
    public function sections(): BelongsToMany
    {
        $relation = $this->belongsToMany(Section::class, 'region_section_widgets')
            ->using(RegionSectionWidget::class)
            ->as('pivot')
            ->withPivot(['widget_id', 'position', 'config', 'flag', 'metadata'])
            ->wherePivot('flag', Flag::PUBLISHED)
            ->wherePivotNull('deleted_at');

        return $relation->orderByPivot('position');
    }

    /**
     * Get published widgets in this region, ordered by position.
     */
    public function widgets(): BelongsToMany
    {
        $relation = $this->belongsToMany(Widget::class, 'region_section_widgets')
            ->using(RegionSectionWidget::class)
            ->as('pivot')
            ->withPivot(['section_id', 'position', 'config', 'flag', 'metadata'])
            ->wherePivot('flag', Flag::PUBLISHED)
            ->wherePivotNull('deleted_at');

        return $relation->orderByPivot('position');
    }
}
