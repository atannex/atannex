<?php

namespace Atannex\Repositories\Traits;

use App\Models\Posts\Post;
use App\Models\Pages\Category;
use App\Models\Regions\Region;
use Atannex\Traits\Resolver;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Trait for handling post-related database queries in a structured and reusable manner.
 */
trait PostQuery
{
    use Resolver;

    protected const DEFAULT_PAGINATION_LIMIT   = 50;

    protected const DEFAULT_RECENT_POSTS_LIMIT = 5;

    protected const DEFAULT_POPULAR_TAGS_LIMIT = 12;

    /**
     * Get paginated posts for a category.
     */
    public function getPostsByCategory(?Category $category, int $limit = self::DEFAULT_PAGINATION_LIMIT): LengthAwarePaginator
    {
        $categoryIds = $this->getModelWithDescendantsIds($category);

        return $this->paginatePosts(
            Post::published()->whereIn('category_id', $categoryIds)->with($this->defaultRelations()),
            $limit
        );
    }

    /**
     * Get paginated posts for a region.
     */
    public function getPostsByRegion(?Region $region, int $limit = 15): LengthAwarePaginator
    {
        $regionIds = $this->getModelWithDescendantsIds($region);

        $query = Post::whereHas('category', fn($q) => $q->doesntHave('children'))
            ->whereHas('regions', fn($q) => $q->whereIn('region_id', $regionIds))
            ->with(['category', 'regions']);

        return $this->paginatePosts($query, $limit);
    }

    /**
     * Get recent posts in the same root category, excluding a post.
     */
    public function getRecentPosts(?Post $post, int $limit = self::DEFAULT_RECENT_POSTS_LIMIT): Collection
    {
        abort_if(!$post?->category, 404);

        $category = $this->getRootCategory($post->category);
        abort_if(!$category, 404);

        $collection = $this->buildPostQuery($category, $post->id)
            ->latest()
            ->take($this->sanitizeLimit($limit))
            ->get();

        abort_if($collection->isEmpty(), 404);

        return $collection;
    }

    /**
     * Get paginated posts by author slug.
     */
    public function getPostsByAuthor(?string $slugPath, int $limit = self::DEFAULT_PAGINATION_LIMIT): LengthAwarePaginator
    {
        abort_if(!$slugPath || $slugPath === '0', 404);

        $author = $this->resolveAuthor($slugPath) ?: abort(404);

        $query = Post::published()
            ->where('author_id', $author->id)
            ->with([
                'author' => $this->authorWithPostCount(),
                'category'
            ])
            ->latest();

        return $this->paginatePosts($query, $limit)->appends(['slug' => $slugPath]);
    }

    /**
     * Get paginated posts filtered by year and month.
     */
    public function getPostsByDate(?string $yearMonth = null, int $perPage = 15): LengthAwarePaginator
    {
        $query = Post::query()->whereNotNull('published_at');

        if ($yearMonth) {
            ['year' => $year, 'month' => $month] = $this->parseDateSlug($yearMonth);

            $query->when($year, fn($q) => $q->whereYear('published_at', $year))
                ->when($month, fn($q) => $q->whereMonth('published_at', $month));
        }

        return $this->paginatePosts($query->latest('published_at'), $perPage);
    }

    /**
     * Build a reusable query for posts in a category, optionally excluding a post.
     */
    protected function buildPostQuery(?Category $category, ?int $excludeId = null): Builder
    {
        $categoryIds = $this->getCategoryTreeIds($category);
        abort_if($categoryIds->isEmpty(), 404);

        $query = Post::published()
            ->whereIn('category_id', $categoryIds)
            ->with($this->defaultRelations());

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query;
    }

    /**
     * Helper to get IDs of a model and all its descendants.
     */
    protected function getModelWithDescendantsIds($model): Collection
    {
        abort_if(!$model, 404);

        return $model->getDescendants()->push($model)->pluck('id');
    }

    /**
     * Helper to paginate a query and abort if empty.
     */
    protected function paginatePosts(Builder $query, int $limit): LengthAwarePaginator
    {
        $paginator = $query->latest()->paginate($this->sanitizeLimit($limit));
        abort_if($paginator->isEmpty(), 404);

        return $paginator;
    }
}
