<?php

namespace Atangageih\Repositories;

use App\Enums\Flag;
use App\Models\Regions\Employee;
use Illuminate\Support\Collection;
use Atangageih\Contracts\CategoryInterface;
use Atangageih\Repositories\Traits\TagQuery;
use Atangageih\Repositories\Traits\PostQuery;
use Atangageih\Repositories\Traits\CategoryTree;

/**
 * Repository class for managing category-related operations.
 *
 * Implements the CategoryInterface for consistent category handling.
 * Leverages traits for reusable query logic (CategoryTree, PostQuery, TagQuery).
 */
class CategoryRepository implements CategoryInterface
{
    use CategoryTree;
    use PostQuery;
    use TagQuery;

    /**
     * Retrieve published social media profiles for a given user.
     *
     * @param Employee $employee The user to fetch social media profiles for.
     * @return Collection Collection of published social media profiles, ordered by display order.
     */
    public function getPublishedEmployeeSocialMedia(Employee $employee): Collection
    {
        return $employee->socialMedia()
            ->where('flag', Flag::PUBLISHED)
            ->orderBy('order')
            ->get();
    }
}
