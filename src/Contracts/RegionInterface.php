<?php

namespace Atannex\Contracts;

use App\Models\Regions\Region;
use Illuminate\Support\Collection;

/**
 * Interface RegionInterface
 *
 * Contract for retrieving and categorizing regions.
 */
interface RegionInterface
{
    /**
     * Retrieve a main region by its slug path.
     *
     * @param string $slug_path The unique slug identifier for the region.
     * @return Region|null The matching Region instance or null if not found.
     */
    public function getMainRegion(string $slug_path): ?Region;

    /**
     * Get all category-related regions.
     *
     * @return Collection<int, Region> Collection of category Region instances.
     */
    public function getAllCategoryRegions(): Collection;

    /**
     * Get all main regions.
     *
     * @return Collection<int, Region> Collection of main Region instances.
     */
    public function getAllMainRegions(): Collection;
}
