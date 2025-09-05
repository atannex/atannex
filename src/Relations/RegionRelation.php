<?php

namespace Atannex\Relations;

use App\Models\Posts\Post;
use App\Models\Regions\Ruler;
use App\Models\Regions\Region;
use App\Models\Pivots\PostRegion;
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
}
