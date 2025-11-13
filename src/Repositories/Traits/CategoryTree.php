<?php

namespace Atannex\Repositories\Traits;

use App\Enums\Flag;
use App\Models\Others\SocialMedia;
use App\Models\Regions\Category;
use App\Models\Regions\Employee;
use Atannex\Helpers\HasMedia;
use Atannex\Traits\HasTree;
use Illuminate\Support\Collection;

trait CategoryTree
{
    use HasMedia;
    use HasTree;

    /**
     * Global max results for consistent UX across category queries.
     */
    protected const CATEGORY_LIMIT = 12;

    /**
     * Get formatted social profiles for an employee.
     */
    public function employeeSocial(Employee $employee): Collection
    {
        return $employee->socialMedia()
            ->nonGlobal()
            ->ordered()
            ->get()
            ->map(fn (SocialMedia $media) => $this->mapSocialMedia($media))
            ->filter()
            ->values();
    }

    /**
     * Get related categories from the same tree,
     * excluding the current category.
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
