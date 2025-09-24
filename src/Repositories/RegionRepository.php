<?php

namespace Atannex\Repositories;

use App\Enums\Flag;
use App\Models\Regions\Region;
use App\Models\Regions\Category;
use Illuminate\Support\Collection;
use Atannex\Contracts\RegionInterface;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
            ->with(['children' => function (HasMany $query) {
                $query->where('flag', Flag::PUBLISHED)
                    ->with('children');
            }])
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
            ->with(['childrenRecursive'])
            ->get();
    }

    /**
     * Retrieve a single active main region by slug, including only active sections and widgets.
     *
     * @param string $slug The slug of the region.
     * @return Region The matching Region instance.
     */
    public function getMainRegion(string $slug): ?Region
    {
        return Region::query()
            ->where('flag', Flag::PUBLISHED)
            ->where('slug', $slug)
            ->with([
                'sections' => function ($query) {
                    $query->wherePivot('flag', Flag::PUBLISHED)
                        ->with([
                            'widgets' => function ($query) {
                                $query->wherePivot('flag', Flag::PUBLISHED);
                            }
                        ]);
                }
            ])
            ->first();
    }
}
