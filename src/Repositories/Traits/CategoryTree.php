<?php

namespace Atannex\Repositories\Traits;

use App\Models\Pages\Category;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait for managing category tree operations efficiently.
 */
trait CategoryTree
{
    /**
     * Retrieves the root category for a given category.
     */
    protected function getRootCategory(Category $category): Category
    {
        return $category->getAncestors()->first() ?? $category;
    }

    /**
     * Retrieves category IDs within a category tree, excluding a given ID if provided.
     */
    protected function getCategoryTreeIds(Category $category, ?int $excludeId = null): Collection
    {
        $ids = $category->getDescendantsAndSelf()->pluck('id');

        return $this->excludeId($ids, $excludeId);
    }

    /**
     * Retrieves related categories within a category tree.
     */
    protected function getRelatedCategories(Category $ancestor, ?int $excludeId = null, int $limit = 12): Collection
    {
        return Category::query()
            ->whereIn('id', $this->getCategoryTreeIds($ancestor, $excludeId))
            ->whereDoesntHave('children')
            ->withCount(['posts' => $this->publishedPostsScope()])
            ->orderByDesc('posts_count')
            ->take($limit)
            ->get();
    }

    /**
     * Retrieves related categories for a given category, excluding itself.
     */
    public function getRelatedCategoriesForCategory(Category $category, int $limit = 12): Collection
    {
        return $this->getRelatedCategories(
            $this->getRootCategory($category),
            $category->id,
            $limit
        );
    }

    /* -----------------------------------------------------------------
     |  Private helpers
     | -----------------------------------------------------------------
     */

    /**
     * Exclude a given ID from a collection of IDs.
     */
    private function excludeId(Collection $ids, ?int $excludeId): Collection
    {
        return $excludeId !== null
            ? $ids->reject(fn($id) => $id === $excludeId)->values()
            : $ids->values();
    }

    /**
     * Scope for counting only published posts.
     */
    private function publishedPostsScope(): \Closure
    {
        return fn(Builder $query) => $query->published(false);
    }
}
