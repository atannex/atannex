<?php

namespace Atannex\Repositories;

use App\Enums\Flag;
use App\Models\Regions\Region;
use App\Models\Regions\Category;
use Illuminate\Support\Collection;
use Atannex\Contracts\RegionInterface;

/**
 * Repository handling region and category retrieval.
 *
 * Implements the RegionInterface without internal error handling.
 * Assumes all data exists and queries always return valid results.
 */
class RegionRepository implements RegionInterface
{
    /**
     * Retrieve all top-level published region categories with their children recursively loaded.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, Category>
     */
    public function getAllCategoryRegions(): Collection
    {
        return Category::query()
            ->where('flag', Flag::PUBLISHED)
            ->whereNull('parent_id')
            ->with('children')
            ->get();
    }

    /**
     * Retrieve all published and active main regions along with their nested children.
     *
     * @return Collection<int, Region>
     */
    public function getAllMainRegions(): Collection
    {
        return Region::query()
            ->where('flag', Flag::PUBLISHED)
            ->whereNull('parent_id')
            ->with('childrenRecursive')
            ->get();
    }

    /**
     * Retrieve a single published region by slug,
     * including only published sections and widgets.
     */
    public function getMainRegion(string $slug): ?Region
    {
        return Region::query()
            ->where('flag', Flag::PUBLISHED)
            ->where('slug', $slug)
            ->with([
                'sections',
                'sections.widgets',
            ])
            ->first();
    }
}
