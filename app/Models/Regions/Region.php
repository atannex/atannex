<?php

namespace App\Models\Regions;

use App\Contracts\Sluggable;
use App\Enums\Flag;
use Atannex\Enables\EnableSlug;
use Atannex\Enables\EnableScope;
use Atannex\Filters\GetHierarchy;
use Atannex\Relations\RegionRelation;
use Atannex\Traits\Bootable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Region
 *
 * Represents a hierarchical region with Sluggable support.
 */
class Region extends Model implements Sluggable
{
    use SoftDeletes;
    use EnableSlug;
    use EnableScope;
    use GetHierarchy;
    use Bootable;
    use RegionRelation;

    protected string $slugSource = 'name';

    protected $fillable = [
        'name',
        'flag',
        'slug',
        'territory',
        'logo',
        'description',
        'slug_path',
        'parent_id',
    ];

    protected $casts = [
        'flag' => Flag::class,
    ];

    /**
     * Sluggable interface: return base for pivot slug.
     */
    public function getSlugBase(): ?string
    {
        if ($this->parent) {
            return $this->parent->buildDynamicSlugPath();
        }
        return null;
    }

    /**
     * Sluggable interface: return own slug segment.
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * Cascade slug updates to child regions.
     */
    public function cascadeSlugPathUpdates(): void
    {
        foreach ($this->children as $child) {
            $child->slug_path = $child->buildDynamicSlugPath();
            $child->save();
            $child->cascadeSlugPathUpdates();
        }
    }

    /**
     * Clear related slug paths (if needed on delete).
     */
    public function clearRelatedSlugPaths(): void
    {
        foreach ($this->children as $child) {
            $child->slug_path = null;
            $child->save();
            $child->clearRelatedSlugPaths();
        }
    }
}
