<?php

namespace Atannex\Relations;

use App\Models\Pivots\PostRegion;
use App\Models\Posts\Post;
use App\Models\Regions\Region as RegionsRegion;
use App\Models\Regions\Ruler;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait RegionRelation
{
    /**
     * Parent region (self-referential).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(RegionsRegion::class, 'parent_id');
    }

    /**
     * Child regions.
     */
    public function children(): BelongsTo
    {
        return $this->hasMany(RegionsRegion::class, 'parent_id');
    }

    public function ruler(): HasOne
    {
        return $this->hasOne(Ruler::class);
    }

    /**
     * Posts related to this region (many-to-many).
     */
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_region')
            ->withTimestamps()
            ->withPivot('deleted_at')
            ->using(PostRegion::class);
    }
}
