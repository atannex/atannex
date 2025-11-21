<?php

declare(strict_types=1);

namespace Atannex\Services;

use App\Models\Regions\Category;
use App\Models\Regions\Region;
use Atannex\Contracts\RegionInterface;
use Illuminate\Support\Collection;

/**
 * Service layer for handling region-related operations.
 */
final class RegionService
{
    public function __construct(
        protected readonly RegionInterface $interface
    ) {}

    /**
     * Retrieve a parent region (main region) by slug.
     *
     * @param string $slug
     * @return Region
     */
    public function getRegionBySlug(string $slug): ?Region
    {
        return $this->interface->getRegionBySlug($slug);
    }

    /**
     * Retrieve all root category regions.
     *
     * @return Collection<int, Category>
     */
    public function getRootCategoryRegions(): Collection
    {
        return $this->interface->getRootCategoryRegions();
    }

    /**
     * Retrieve all root (top-level) regions.
     *
     * @return Collection<int, Region>
     */
    public function getRootRegions(): Collection
    {
        return $this->interface->getRootRegions();
    }
}
