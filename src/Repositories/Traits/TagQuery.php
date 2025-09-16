<?php

namespace Atannex\Repositories\Traits;

use App\Models\Tags\Tag;
use App\Models\Posts\Post;
use Atannex\Helpers\HasQuery;
use App\Models\Pages\Category;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Trait for handling tag-related database queries in a structured and reusable manner.
 */
trait TagQuery
{
    use HasQuery;

    protected const DEFAULT_PAGINATION_LIMIT   = 50;

    protected const DEFAULT_POPULAR_TAGS_LIMIT = 12;

    /**
     * Retrieve paginated posts associated with a specific tag.
     */
    public function getPostsByTag(Tag $tag, int $limit = self::DEFAULT_PAGINATION_LIMIT): LengthAwarePaginator
    {
        return Post::published()
            ->whereHas('tags', fn(Builder $query) => $query->whereKey($tag->id))
            ->with($this->defaultPostRelations())
            ->latest()
            ->paginate($this->sanitizeLimit($limit));
    }

    /**
     * Retrieve popular tags within a tag's category tree.
     */
    public function getPopularTagsByTagCategoryTree(Tag $tag, int $limit = self::DEFAULT_POPULAR_TAGS_LIMIT): Collection
    {
        $categoryIds = $this->getCategoryTreeIdsFromTag($tag);

        return Tag::query()
            ->whereHas('posts', fn(Builder $query) => $this->postsInCategoryTree($query, $categoryIds))
            ->withCount(['posts' => fn(Builder $query) => $this->postsInCategoryTree($query, $categoryIds)])
            ->orderByDesc('posts_count')
            ->take($this->sanitizeLimit($limit))
            ->get();
    }

    /**
     * Retrieve the root category for a given tag based on its first associated post.
     */
    protected function getRootCategoryFromTag(Tag $tag): Category
    {
        $firstPost = $tag->posts()->with('category.parent')->first();

        return $this->getRootCategory($firstPost->category);
    }

    /**
     * Retrieve category IDs within a tag's category tree.
     */
    protected function getCategoryTreeIdsFromTag(Tag $tag): Collection
    {
        $rootCategory = $this->getRootCategoryFromTag($tag);

        return $this->getCategoryTreeIds($rootCategory);
    }

    /**
     * Retrieve related categories for a given tag based on its root category.
     */
    public function getRelatedCategoriesForTag(Tag $tag): Collection
    {
        $rootCategory = $this->getRootCategoryFromTag($tag);

        return $this->getRelatedCategories($rootCategory);
    }

    /* -----------------------------------------------------------------
     |  Private helpers
     | -----------------------------------------------------------------
     */

    /**
     * Default eager-load relations for posts.
     */
    private function defaultPostRelations(): array
    {
        return ['category', 'tags', 'author'];
    }

    /**
     * Add published + category filter for posts in a tag's category tree.
     */
    private function postsInCategoryTree(Builder $query, Collection $categoryIds): Builder
    {
        return $query->published()->whereIn('category_id', $categoryIds);
    }
}
