<?php

declare(strict_types=1);

namespace Atannex\Services;

use App\Models\Regions\Region;
use Illuminate\Support\Collection;
use Atannex\Contracts\RegionInterface;

/**
 * Service layer for handling region-related operations.
 */
final class RegionService
{
    public function __construct(
        protected readonly RegionInterface $interface
    ) {}

    /**
     * Retrieve a specific active main region by slug.
     *
     * @param string $slug
     * @return Region The matching Region instance.
     *
     */
    public function getMainRegion(string $slug): ?Region
    {
        return $this->interface->getMainRegion($slug);
    }

    /**
     * Retrieve all top-level published region categories.
     *
     * @return Collection<int, Region>
     */
    public function getAllCategoryRegions(): Collection
    {
        return $this->interface->getAllCategoryRegions();
    }

    /**
     * Retrieve all published and active main regions.
     *
     * @return Collection<int, Region>
     */
    public function getAllMainRegions(): Collection
    {
        return $this->interface->getAllMainRegions();
    }
}
