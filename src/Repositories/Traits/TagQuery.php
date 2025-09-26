<?php

namespace Atannex\Repositories\Traits;

use App\Models\Tags\Tag;
use App\Models\Posts\Post;
use App\Models\Regions\Category;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Trait for handling tag-related database queries in a structured and reusable manner.
 */
trait TagQuery
{
    protected const DEFAULT_PAGINATION_LIMIT   = 50;

    protected const DEFAULT_POPULAR_TAGS_LIMIT = 12;

    /**
     * Get paginated posts associated with a tag.
     */
    public function getPostsByTag(Tag $tag, int $limit = self::DEFAULT_PAGINATION_LIMIT): LengthAwarePaginator
    {
        $query = Post::published()
            ->whereHas('tags', fn (Builder $q) => $q->whereKey($tag->id))
            ->with($this->defaultRelations())
            ->latest();

        return $query->paginate($this->sanitizeLimit($limit));
    }

    /**
     * Get popular tags within a tag's category tree (ordered by post count).
     */
    public function getPopularTagsByTagCategoryTree(Tag $tag, int $limit = self::DEFAULT_POPULAR_TAGS_LIMIT): Collection
    {
        $categoryIds = $this->getCategoryTreeIdsFromTag($tag);

        return Tag::query()
            ->whereHas('posts', fn (Builder $q) => $this->postsInCategoryTree($q, $categoryIds))
            ->withCount(['posts' => fn (Builder $q) => $this->postsInCategoryTree($q, $categoryIds)])
            ->orderByDesc('posts_count')
            ->limit($this->sanitizeLimit($limit))
            ->get();
    }

    /**
     * Get the root category for a tag (based on its first associated post).
     */
    protected function getRootCategoryFromTag(Tag $tag): Category
    {
        $firstPost = $tag->posts()
            ->with('category.parent')
            ->firstOrFail();

        return $this->getRootCategory($firstPost->category);
    }

    /**
     * Get all category IDs in a tag's category tree.
     */
    protected function getCategoryTreeIdsFromTag(Tag $tag): Collection
    {
        return $this->getCategoryTreeIds($this->getRootCategoryFromTag($tag));
    }

    /**
     * Get related categories for a tag (based on its root category).
     */
    public function getRelatedCategoriesForTag(Tag $tag): Collection
    {
        return $this->getRelatedCategories($this->getRootCategoryFromTag($tag));
    }

    /**
     * Apply published + category filter for posts in a tag's category tree.
     */
    private function postsInCategoryTree(Builder $query, Collection $categoryIds): Builder
    {
        return $query->published()->whereIn('category_id', $categoryIds);
    }
}
