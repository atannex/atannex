<?php

namespace Atangageih\Repositories\Traits;

use App\Models\Pages\Category;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait for managing category tree operations in a structured and reusable manner.
 */
trait CategoryTree
{
    /**
     * Retrieve the root category for a given category.
     *
     * @param Category $category The category to find the root for.
     * @return Category The root category or the input category if no ancestors exist.
     */
    protected function getRootCategory(Category $category): Category
    {
        return $category->getAncestors()->first() ?? $category;
    }

    /**
     * Retrieve a collection of category IDs within a category tree.
     *
     * @param Category $category The root category for the tree.
     * @param int|null $excludeId Optional category ID to exclude from the result.
     * @return Collection Collection of category IDs.
     */
    protected function getCategoryTreeIds(Category $category, ?int $excludeId = null): Collection
    {
        $ids = $category->getDescendantsAndSelf()->pluck('id')->unique();
        return $excludeId ? $ids->reject(fn($id) => $id === $excludeId)->values() : $ids->values();
    }

    /**
     * Retrieve related categories within a category tree, excluding specified ID if provided.
     *
     * @param Category $ancestor The ancestor category to base the query on.
     * @param int|null $excludeId Optional category ID to exclude from the result.
     * @return Collection Collection of related categories with post counts.
     */
    protected function getRelatedCategories(Category $ancestor, ?int $excludeId = null): Collection
    {
        return Category::query()
            ->whereIn('id', $this->getCategoryTreeIds($ancestor, $excludeId))
            ->whereDoesntHave('children')
            ->withCount(['posts' => fn(Builder $query) => $query->published()])
            ->orderByDesc('posts_count')
            ->limit(12)
            ->get();
    }

    /**
     * Retrieve related categories for a given category, excluding itself.
     *
     * @param Category $category The category to find related categories for.
     * @return Collection Collection of related categories.
     */
    public function getRelatedCategoriesForCategory(Category $category): Collection
    {
        return $this->getRelatedCategories($this->getRootCategory($category), $category->id)->take(15);
    }
}
