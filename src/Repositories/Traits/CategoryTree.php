<?php

namespace Atannex\Repositories\Traits;

use App\Enums\Flag;
use App\Models\Others\SocialMedia;
use App\Models\Regions\Employee;
use App\Models\Regions\Category;
use Atannex\Helpers\HasMedia;
use Illuminate\Support\Collection;
use Atannex\Traits\HasTree;

trait CategoryTree
{
    use HasTree;
    use HasMedia;

    /**
     * Global max results for consistent UX across category queries.
     */
    protected const CATEGORY_LIMIT = 12;

    /**
     * Get formatted social profiles for an employee.
     *
     * @param  Employee $employee
     * @return Collection
     */
    public function employeeSocial(Employee $employee): Collection
    {
        return $employee->socialMedia()
            ->published('non-global')
            ->ordered()
            ->get()
            ->map(fn(SocialMedia $media) => $this->mapSocialMedia($media))
            ->filter()
            ->values();
    }

    /**
     * Get related categories from the same tree,
     * excluding the current category.
     *
     * @param  Category $category
     * @param  int      $limit
     * @return Collection
     */
    public function relatedCategories(Category $category, int $limit = self::CATEGORY_LIMIT): Collection
    {
        return $this->getLeafNodes(
            $this->getRoot($category),
            'posts',
            Flag::PUBLISHED,
            $category->id,
            $limit
        );
    }
}
