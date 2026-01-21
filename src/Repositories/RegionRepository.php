<?php

namespace Atannex\Repositories;

use App\Enums\Flag;
use App\Models\Regions\Category;
use App\Models\Regions\Region;
use Atannex\Contracts\RegionInterface;
use Illuminate\Support\Collection;

/**
 * Repository for retrieving regions and categories.
 *
 * Ensures only published content is returned with optimized eager loading.
 */
class RegionRepository implements RegionInterface
{
    /**
     * Retrieve published root categories with their immediate children loaded.
     *
     * @return Collection<int, Category> Collection of published root Category models with their immediate `children` relation loaded.
     */
    public function getRootCategoryRegions(): Collection
    {
        return Category::query()
            ->flagged(Flag::PUBLISHED)
            ->whereNull('parent_id')
            ->with('descendants')
            ->get();
    }

    /**
     * Retrieve published root regions with their recursively nested children.
     *
     * @return Collection<int, Region> Collection of published root Region models with their recursively nested children.
     */
    public function getRootRegions(): Collection
    {
        return Region::query()
            ->flagged(Flag::PUBLISHED)
            ->whereNull('parent_id')
            ->with('descendants')
            ->get();
    }

    /**
     * Retrieve a published region by its full slug path and eager-load its ordered sections and widgets.
     *
     * Eager-loads `sections` (ordered by pivot `position`) and each section's `widgets` (also ordered by pivot `position`).
     * Pivot attributes (position, config, flag, metadata) on those relationships are available on the returned model.
     *
     * @param string $slug Full slug path of the region.
     * @return \Atannex\Models\Region The matching published Region with `sections` and their `widgets` loaded and ordered.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If no published region matches the provided slug.
     */
    public function getRegionBySlug(string $slug): Region
    {
        return Region::query()
            ->flagged(Flag::PUBLISHED)
            ->where('slug_path', $slug)
            ->with([
                'sections' => fn($query) => $query->orderByPivot('position'),
                'sections.widgets' => fn($query) => $query->orderByPivot('position'),
            ])
            ->firstOrFail();
    }

    /**
     * Retrieve a published region by its full slug path with its widgets loaded as a flat, ordered list.
     *
     * The `widgets` relation is eager loaded and ordered by the pivot `position`.
     *
     * @param string $slug The full slug path of the region.
     * @return \Atannex\Models\Region The region model with the `widgets` relation populated.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If no published region matches the given slug.
     */
    public function getRegionBySlugWithFlatWidgets(string $slug): Region
    {
        return Region::query()
            ->flagged(Flag::PUBLISHED)
            ->where('slug_path', $slug)
            ->with([
                'widgets' => fn($query) => $query->orderByPivot('position'),
            ])
            ->firstOrFail();
    }
}
