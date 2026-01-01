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
     * Get all published root categories with their immediate children.
     *
     * @return Collection<int, Category>
     */
    public function getRootCategoryRegions(): Collection
    {
        return Category::query()
            ->flagged(Flag::PUBLISHED)
            ->whereNull('parent_id')
            ->with('children') // Assumes Category has a hasMany 'children' relation
            ->get();
    }

    /**
     * Get all published top-level regions with fully recursive nested children.
     *
     * @return Collection<int, Region>
     */
    public function getRootRegions(): Collection
    {
        return Region::query()
            ->flagged(Flag::PUBLISHED)
            ->whereNull('parent_id')
            ->with('childrenRecursive')
            ->get();
    }

    /**
     * Get a published region by its full slug path.
     *
     * Loads:
     * - All published sections (ordered by position in this region)
     * - All published widgets within those sections (ordered by position)
     * - All pivot data (position, config, flag, metadata) is automatically included
     *
     * Ideal for rendering structured page layouts (sections → widgets).
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
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
     * Alternative: Get region with all published widgets directly attached.
     *
     * Widgets are ordered by position globally in the region.
     * Each widget's pivot includes section_id, config, position, etc.
     *
     * Useful when you want a flat list of widgets (e.g., for dynamic rendering).
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
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
