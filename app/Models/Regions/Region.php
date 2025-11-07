<?php

namespace App\Models\Regions;

use App\Contracts\Sluggable;
use App\Enums\Flag;
use Atannex\Enables\HasSlug;
use Atannex\Enables\HasScope;
use Atannex\Filters\GetHierarchy;
use Atannex\Relations\RegionRelation;
use Atannex\Traits\HasBootable;
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
    use HasSlug;
    use HasScope;
    use GetHierarchy;
    use HasBootable;
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
        'metadata',
        'parent_id',
    ];

    protected $casts = [
        'flag' => Flag::class,
        'metadata' => 'array',
    ];

    /**
     * Sluggable interface: return base for pivot slug.
     */
    public function getSlugBase(): string
    {
        return $this->parent->buildDynamicSlugPath();
    }

    /**
     * Sluggable interface: return own slug segment.
     */
    public function getSlug(): string
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
     * Clear related slug paths (on delete or reset).
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
