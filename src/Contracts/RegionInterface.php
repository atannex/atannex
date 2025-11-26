<?php

namespace Atannex\Contracts;

use App\Models\Regions\Region;
use Illuminate\Support\Collection;

/**
 * Interface RegionInterface
 *
 * Contract for retrieving and classifying regions.
 */
interface RegionInterface
{
    /**
     * Retrieve a parent region by its slug.
     *
     * Must always return a Region instance.
     * If no region is found, the implementation should handle this internally
     * (e.g., throw an exception or return a default/fallback Region).
     *
     * @param  string  $slug
     * @return Region
     */
    public function getRegionBySlug(string $slug): ?Region;

    /**
     * Retrieve all root category regions.
     *
     * @return Collection<int, Region>
     */
    public function getRootCategoryRegions(): Collection;

    /**
     * Retrieve all root regions (main regions).
     *
     * @return Collection<int, Region>
     */
    public function getRootRegions(): Collection;
}
