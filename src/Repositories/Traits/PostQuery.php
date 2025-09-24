<?php

namespace Atannex\Repositories\Traits;

use App\Models\Posts\Post;
use App\Models\Regions\Region;
use Atannex\Traits\HasResolver;
use App\Models\Regions\Category;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Trait for handling post-related database queries in a structured and reusable manner.
 */
trait PostQuery
{
    use HasResolver;

    protected const DEFAULT_PAGINATION_LIMIT   = 50;
    protected const DEFAULT_RECENT_POSTS_LIMIT = 5;
    protected const DEFAULT_POPULAR_TAGS_LIMIT = 12;

    /**
     * Get paginated posts for a category and its descendants.
     */
    public function getPostsByCategory(?Category $category, int $limit = self::DEFAULT_PAGINATION_LIMIT): LengthAwarePaginator
    {
        $ids = $this->getModelWithDescendantsIds($category);

        $query = Post::published()
            ->whereIn('category_id', $ids)
            ->with($this->defaultRelations());

        return $this->paginatePosts($query, $limit);
    }

    /**
     * Get paginated posts for a region and its descendants.
     */
    public function getPostsByRegion(?Region $region, int $limit = 15): LengthAwarePaginator
    {
        $ids = $this->getModelWithDescendantsIds($region);

        $query = Post::published()
            ->whereHas('category', fn($q) => $q->doesntHave('children'))
            ->whereHas('regions', fn($q) => $q->whereIn('region_id', $ids))
            ->with(['category', 'regions']);

        return $this->paginatePosts($query, $limit);
    }

    /**
     * Get recent posts in the same root category, excluding a specific post.
     */
    public function getRecentPosts(?Post $post, int $limit = self::DEFAULT_RECENT_POSTS_LIMIT): Collection
    {
        if (!$post?->category) {
            abort(404);
        }

        $root = $this->getRootCategory($post->category);

        return $this->buildPostQuery($root, $post->id)
            ->limit($this->sanitizeLimit($limit))
            ->get();
    }

    /**
     * Get paginated posts by author slug.
     */
    public function getPostsByAuthor(?string $slugPath, int $limit = self::DEFAULT_PAGINATION_LIMIT): LengthAwarePaginator
    {
        $author = $this->resolveAuthor($slugPath);

        $query = Post::published()
            ->where('author_id', $author->id)
            ->with([
                'author'   => $this->authorWithPostCount(),
                'category',
            ])
            ->latest();

        return $this->paginatePosts($query, $limit)
            ->appends(['slug' => $slugPath]);
    }

    /**
     * Get paginated posts filtered by year and month (archive style).
     */
    public function getPostsByDate(?string $yearMonth = null, int $limit = 15): LengthAwarePaginator
    {
        $query = Post::query()->whereNotNull('published_at');

        if ($yearMonth) {
            ['year' => $year, 'month' => $month] = $this->parseDateSlug($yearMonth);

            $query->when($year, fn($q) => $q->whereYear('published_at', $year))
                ->when($month, fn($q) => $q->whereMonth('published_at', $month));
        }

        return $this->paginatePosts($query, $limit);
    }

    /**
     * Build a reusable query for posts in a category, optionally excluding one post.
     */
    protected function buildPostQuery(?Category $category, ?int $excludeId = null): Builder
    {
        $ids = $this->getCategoryTreeIds($category);

        $query = Post::published()
            ->whereIn('category_id', $ids)
            ->with($this->defaultRelations())
            ->latest('published_at');

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query;
    }

    /**
     * Get IDs of a model and all its descendants.
     */
    protected function getModelWithDescendantsIds($model): Collection
    {
        return $model->getDescendants()
            ->push($model)
            ->pluck('id');
    }

    /**
     * Paginate a query with a safe limit.
     */
    protected function paginatePosts(Builder $query, int $limit): LengthAwarePaginator
    {
        return $query->latest()->paginate($this->sanitizeLimit($limit));
    }
}
