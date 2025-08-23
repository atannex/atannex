<?php

namespace App\Models\Regions;

use App\Contracts\Sluggable;
use Atannex\Traits\Bootable;
use Atannex\Enables\EnableSlug;
use Atannex\Enables\EnableScope;
use Atannex\Filters\GetHierarchy;
use Atannex\Relations\RegionRelation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Region extends Model implements Sluggable
{
    use SoftDeletes;
    use RegionRelation;
    use EnableSlug;
    use EnableScope;
    use Bootable;
    use GetHierarchy;

    protected string $slugSource = 'name';

    protected $fillable = [
        'name',
        'flag',
        'slug',
        'slug_path',
        'type',
        'logo',
        'description',
        'parent_id',
    ];

    protected $casts = [
        'flag' => 'string',
    ];

    /**
     * ---------------------------
     * Sluggable Implementation
     * ---------------------------
     */

    public function getSlugBase(): ?string
    {
        return $this->parent ? $this->parent->slug_path : null;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function cascadeSlugPathUpdates(): void
    {
        foreach ($this->children as $child) {
            $child->rebuildSlugPath();
            $child->save();

            // recursive cascade
            $child->cascadeSlugPathUpdates();
        }
    }

    public function clearRelatedSlugPaths(): void
    {
        foreach ($this->children as $child) {
            $child->slug_path = null;
            $child->save();

            // recursive clear
            $child->clearRelatedSlugPaths();
        }
    }

    /**
     * Rebuild and update this region's slug_path.
     */
    public function rebuildSlugPath(): void
    {
        $this->slug_path = $this->buildSlugPath(
            $this->getSlugBase(),
            $this->getSlug()
        );
    }
}
