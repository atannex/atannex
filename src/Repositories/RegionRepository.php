<?php

namespace Atannex\Repositories;

use App\Enums\Flag;
use App\Models\Regions\Category;
use App\Models\Regions\Region;
use Atannex\Contracts\RegionInterface;
use Illuminate\Support\Collection;

/**
 * Repository handling region and category retrieval.
 *
 * Designed to always return non-null results where required.
 */
class RegionRepository implements RegionInterface
{
    /**
     * Retrieve all published top-level region categories with their children.
     *
     * @return Collection<int, Category>
     */
    public function getRootCategoryRegions(): Collection
    {
        return Category::query()
            ->where('flag', Flag::PUBLISHED)
            ->whereNull('parent_id')
            ->with('children')
            ->get();
    }

    /**
     * Retrieve all published top-level regions (main regions)
     * with their nested children loaded recursively.
     *
     * @return Collection<int, Region>
     */
    public function getRootRegions(): Collection
    {
        return Region::query()
            ->where('flag', Flag::PUBLISHED)
            ->whereNull('parent_id')
            ->with('childrenRecursive')
            ->get();
    }

    /**
     * Retrieve a single parent region by slug.
     * Always returns a Region or throws an exception.
     */
    public function getRegionBySlug(string $slug): ?Region
    {
        return Region::query()
            ->where('flag', Flag::PUBLISHED)
            ->where('slug_path', $slug)
            ->with([
                'sections',
                'sections.widgets',
            ])
            ->first();
    }
}
