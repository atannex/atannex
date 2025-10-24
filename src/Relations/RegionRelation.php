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
     * Parent region (self-referential).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'parent_id');
    }

    /**
     * Direct published children.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Region::class, 'parent_id')
            ->where('flag', Flag::PUBLISHED);
    }

    /**
     * Recursive children relationship (all descendants).
     */
    public function childrenRecursive(): HasMany
    {
        return $this->children()->with('childrenRecursive');
    }

    /**
     * Ruler associated with this region.
     */
    public function ruler(): HasOne
    {
        return $this->hasOne(Ruler::class);
    }

    /**
     * Posts related to this region (many-to-many pivot).
     */
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_region')
            ->using(PostRegion::class)
            ->withTimestamps();
    }

    public function widgets(): BelongsToMany
    {
        return $this->belongsToMany(Widget::class, 'region_section_widgets')
            ->using(RegionSectionWidget::class)
            ->withPivot(['section_id', 'config', 'flag', 'metadata'])
            ->wherePivotNull('deleted_at');
    }

    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(Section::class, 'region_section_widgets')
            ->using(RegionSectionWidget::class)
            ->withPivot(['widget_id', 'config', 'flag', 'metadata'])
            ->wherePivotNull('deleted_at');
    }
}
