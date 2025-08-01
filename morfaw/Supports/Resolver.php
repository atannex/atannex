<?php

namespace Morfaw\Supports;

use App\Models\Pages\Category;
use App\Models\Regions\Employee;

trait Resolver
{
    /**
     * Extract slugs from a slug path.
     */
    protected function extractSlugs(string $slug): array
    {
        return array_filter(explode('/', trim($slug, '/')));
    }

    /**
     * Resolve a category from a nested slug path.
     */
    protected function resolveCategoryFromSlugs(string $slug): ?Category
    {
        $parentId = null;

        foreach ($this->extractSlugs($slug) as $slugPart) {
            $category = $this->findCategory($slugPart, $parentId);

            if (!$category) {
                return null;
            }

            $parentId = $category->id;
        }

        return $category ?? null;
    }

    /**
     * Find a single category by slug and parent ID.
     */
    protected function findCategory(string $slug, ?int $parentId): ?Category
    {
        return Category::query()
            ->with('sections')
            ->where([
                ['slug', '=', $slug],
                ['parent_id', '=', $parentId],
            ])
            ->published()
            ->first();
    }

    /**
     * Resolve an employee author by the user's slug.
     *
     * @param string $slug
     * @return Employee|null
     */
    protected function resolveAuthorBySlug(string $slug): ?Employee
    {
        return Employee::whereHas('user', function ($query) use ($slug) {
            $query->where('slug', $slug);
        })->with('user')->first();
    }
}
