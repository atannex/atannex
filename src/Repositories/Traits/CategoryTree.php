<?php

namespace Atannex\Repositories\Traits;

use Closure;
use App\Models\Pages\Category;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait for managing category tree operations efficiently.
 */
trait CategoryTree
{
    /**
     * Get the root category for a given category.
     */
    protected function getRootCategory(Category $category): Category
    {
        return $category->getAncestors()->last() ?: $category;
    }

    /**
     * Get IDs of a category tree, optionally excluding a given ID.
     */
    protected function getCategoryTreeIds(Category $category, ?int $excludeId = null): Collection
    {
        return $this->excludeId(
            $category->getDescendants()->pluck('id')->push($category->id),
            $excludeId
        );
    }

    /**
     * Get related categories for a given ancestor category, excluding optional ID.
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
     * Get related categories for a specific category, excluding itself.
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
        return $excludeId ? $ids->reject(fn($id) => $id === $excludeId)->values() : $ids->values();
    }

    /**
     * Scope to count only published posts.
     */
    private function publishedPostsScope(): Closure
    {
        return fn(Builder $query) => $query->published();
    }
}
