<?php

namespace Atannex\Relations;

use App\Models\Posts\Post;
use App\Models\Regions\Section;
use App\Models\Regions\Ruler;
use App\Models\Regions\Region;
use App\Models\Pivots\PostRegion;
use App\Models\Pivots\RegionSection;
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
     * Child regions.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Region::class, 'parent_id');
    }

    /**
     * Recursive children relationship.
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
            ->withTimestamps()
            ->using(PostRegion::class);
    }

    /**
     * Get the sections attached to this page through a many-to-many relationship.
     *
     * @return BelongsToMany<Section>
     */
    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(Section::class, 'region_section')
            ->using(RegionSection::class)
            ->withPivot([
                'config',
                'deleted_at',
            ])
            ->withTimestamps()
            ->wherePivot('deleted_at')
            ->orderBy('region_section.position');
    }
}
