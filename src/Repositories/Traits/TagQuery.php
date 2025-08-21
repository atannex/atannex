<?php

namespace Atannex\Repositories\Traits;

use App\Models\Tags\Tag;
use App\Models\Posts\Post;
use App\Models\Pages\Category;
use Illuminate\Support\Collection;
use Atannex\Helpers\MediaHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Trait for handling tag-related database queries in a structured and reusable manner.
 */
trait TagQuery
{
    protected const DEFAULT_PAGINATION_LIMIT      = 50;

    protected const DEFAULT_POPULAR_TAGS_LIMIT    = 12;

    /**
     * Retrieve paginated posts associated with a specific tag.
     *
     * @param Tag $tag The tag to filter posts by.
     * @param int $limit Number of posts per page (default: 15).
     * @return LengthAwarePaginator Paginated posts collection.
     */
    public function getPostsByTag(Tag $tag, int $limit = self::DEFAULT_PAGINATION_LIMIT): LengthAwarePaginator
    {
        return Post::published()
            ->whereHas('tags', fn(Builder $query) => $query->whereKey($tag->id))
            ->with(['category', 'tags', 'author'])
            ->latest()
            ->paginate($limit);
    }

    /**
     * Retrieve popular tags within a tag's category tree.
     *
     * @param Tag|null $tag The tag to base the category tree on, or null for no filtering.
     * @param int $limit Number of popular tags to retrieve (default: 12).
     * @return Collection Collection of popular tags with post counts.
     */
    public function getPopularTagsByTagCategoryTree(?Tag $tag, int $limit = self::DEFAULT_POPULAR_TAGS_LIMIT): Collection
    {
        $categoryIds = $this->getCategoryTreeIdsFromTag($tag);

        return Tag::whereHas('posts', fn(Builder $query) => $query->published()->whereIn('category_id', $categoryIds))
            ->withCount(['posts' => fn(Builder $query) => $query->published()->whereIn('category_id', $categoryIds)])
            ->orderByDesc('posts_count')
            ->take($limit)
            ->get();
    }

    /**
     * Retrieve the root category for a given tag based on its first associated post.
     *
     * @param Tag|null $tag The tag to find the root category for.
     * @return Category|null The root category, or null if no associated post exists.
     */
    protected function getRootCategoryFromTag(?Tag $tag): ?Category
    {
        if (is_null($tag)) {
            return null;
        }

        $firstPost = $tag->posts()->with('category.parent')->first();

        return $firstPost ? $this->getRootCategory($firstPost->category) : null;
    }

    /**
     * Retrieve category IDs within a tag's category tree.
     *
     * @param Tag|null $tag The tag to base the category tree on.
     * @return Collection Collection of category IDs, or empty collection if no root category.
     */
    protected function getCategoryTreeIdsFromTag(?Tag $tag): Collection
    {
        $rootCategory = $this->getRootCategoryFromTag($tag);

        return $rootCategory ? $this->getCategoryTreeIds($rootCategory) : collect();
    }

    /**
     * Retrieve related categories for a given tag based on its root category.
     *
     * @param Tag $tag The tag to find related categories for.
     * @return Collection Collection of related categories.
     */
    public function getRelatedCategoriesForTag(Tag $tag): Collection
    {
        $rootCategory = $this->getRootCategoryFromTag($tag);

        return $this->getRelatedCategories($rootCategory);
    }
}
