<?php

namespace Atannex\Repositories\Traits;

use App\Models\Others\SocialMedia;
use App\Models\Regions\Employee;
use App\Models\Regions\Category;
use Illuminate\Support\Collection;

trait CategoryTree
{
    /**
     * Global limit applied everywhere in the category tree system.
     */
    protected const CATEGORY_LIMIT = 12;

    /**
     * Get globally published employee social profiles.
     *
     * @param Employee $employee
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
     * Always use the global category limit.
     *
     * @param Category $category
     * @return Collection
     */
    public function relatedCategories(Category $category, $limit = self::CATEGORY_LIMIT): Collection
    {
        return $this->leafCategories(
            $this->root($category),
            $category->id,
            $limit
        );
    }
}
