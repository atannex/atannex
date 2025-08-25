<?php

namespace Atannex\Repositories\Traits;

use App\Models\Posts\Post;
use Atannex\Helpers\Query;
use Atannex\Traits\Resolver;
use App\Models\Pages\Category;
use App\Models\Regions\Region;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Trait for handling post-related database queries in a structured and reusable manner.
 */
trait PostQuery
{

    use Query;
    use Resolver;

    protected const DEFAULT_PAGINATION_LIMIT   = 50;

    protected const DEFAULT_RECENT_POSTS_LIMIT = 5;

    protected const DEFAULT_POPULAR_TAGS_LIMIT = 12;

    /**
     * Retrieve paginated posts for a given category.
     */
    public function getPostsByCategory(?Category $category, int $limit = self::DEFAULT_PAGINATION_LIMIT): LengthAwarePaginator
    {
        return $this->buildPostQuery($category)
            ->latest()
            ->paginate($this->sanitizeLimit($limit));
    }

    /**
     * Retrieve posts associated with a specific region, limited by a specified number.
     *
     * This method fetches posts that belong to leaf categories (categories without children)
     * and are linked to the provided region. The posts are returned with their related
     * categories and regions, ordered by latest creation date.
     *
     * @param Region $region The region entity to filter posts by.
     * @param int $limit Optional. The maximum number of posts to return. Default is 15.
     */
    public function getPostsByRegion(?Region $region, int $limit = 15): LengthAwarePaginator
    {
        $regionIds = $region->getDescendantsAndSelf()->pluck('id');

        return Post::whereHas('category', function ($query) {
            $query->doesntHave('children');
        })
            ->whereHas('regions', function ($query) use ($regionIds) {
                $query->whereIn('region_id', $regionIds);
            })
            ->with(['category', 'regions'])
            ->latest()
            ->paginate($this->sanitizeLimit($limit));
    }

    /**
     * Retrieve recent posts for a given post's category, excluding the post itself.
     */
    public function getRecentPosts(?Post $post, int $limit = self::DEFAULT_RECENT_POSTS_LIMIT): Collection
    {
        if (!$post?->category) {
            return collect();
        }

        $category = $this->getRootCategory($post->category);
        if (is_null($category)) {
            return collect();
        }

        return $this->buildPostQuery($category, $post->id)
            ->latest()
            ->take($this->sanitizeLimit($limit))
            ->get();
    }

    /**
     * Retrieve paginated posts by an author identified by their slug.
     */
    public function getPostsByAuthor(?string $slugPath, int $limit = self::DEFAULT_PAGINATION_LIMIT): LengthAwarePaginator
    {
        if ($slugPath === null || $slugPath === '' || $slugPath === '0') {
            return $this->emptyPaginator($limit);
        }

        if (!$author = $this->resolveAuthor($slugPath)) {
            return $this->emptyPaginator($limit);
        }

        return Post::published()
            ->where('author_id', $author->id)
            ->with([
                'author'   => $this->authorWithPostCount(),
                'category'
            ])
            ->orderByDesc('created_at')
            ->paginate($this->sanitizeLimit($limit))
            ->appends(['slug' => $slugPath]);
    }

    /**
     * Build a query for published posts within a category tree.
     */
    protected function buildPostQuery(?Category $category, ?int $excludeId = null): Builder
    {
        $categoryIds = $category instanceof Category ? $this->getCategoryTreeIds($category) : collect();

        if ($categoryIds->isEmpty()) {
            return $this->emptyPostQuery();
        }

        $query = Post::published()
            ->whereIn('category_id', $categoryIds)
            ->with($this->defaultRelations());

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query;
    }

    /**
     * Retrieves paginated posts filtered by an optional year and month slug.
     *
     * This method queries published posts, optionally filtering by a date slug containing
     * year and/or month components. If a date slug is provided, it is parsed to extract
     * year and month, and the query is filtered accordingly. Posts are ordered by publication
     * date in descending order and returned as a paginated result.
     *
     * @param string|null $yearMonth The date slug (e.g., '2023' or '2023-10') to filter posts by year and/or month, or null for no date filter.
     * @param int $perPage The number of posts per page for pagination (default: 15).
     * @return LengthAwarePaginator A paginated collection of posts matching the specified criteria.
     */
    public function getPostsByDate(?string $yearMonth = null, int $perPage = 15): LengthAwarePaginator
    {
        $query = Post::query()->whereNotNull('published_at');

        if ($yearMonth) {
            ['year' => $year, 'month' => $month] = $this->parseDateSlug($yearMonth);

            $query->when($year, fn($q) => $q->whereYear('published_at', $year))
                ->when($month, fn($q) => $q->whereMonth('published_at', $month));
        }

        return $query->orderByDesc('published_at')->paginate($perPage);
    }
}
