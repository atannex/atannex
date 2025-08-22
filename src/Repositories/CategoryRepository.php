<?php

namespace Atannex\Repositories;

use App\Models\Regions\Employee;
use Illuminate\Support\Collection;
use Atannex\Contracts\CategoryInterface;
use Atannex\Repositories\Traits\TagQuery;
use Atannex\Repositories\Traits\PostQuery;
use Atannex\Repositories\Traits\CategoryTree;

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
     * Retrieve published social media profiles for a given employee.
     *
     * @param Employee $employee The employee to fetch social media profiles for.
     * @return Collection<int, \App\Models\Regions\EmployeeSocialMedia>
     */
    public function getPublishedEmployeeSocialMedia(Employee $employee): Collection
    {
        return $employee->socialMedia()
            ->published(true)
            ->ordered()
            ->get();
    }
}
