<?php

namespace Atannex\Repositories\Traits;

use App\Models\Posts\Post;
use App\Models\Regions\Region;
use App\Models\Regions\Category;
use Atannex\Traits\HasResolver;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

trait PostQuery
{
    use HasResolver;

    protected const PAGINATION_LIMIT = 15;

    protected const RECENT_LIMIT = 5;

    /**
     * Paginate posts under category tree.
     */
    public function postsByCategory(Category $category, int $limit = self::PAGINATION_LIMIT): LengthAwarePaginator
    {
        return $this->paginatePosts(
            Post::published()
                ->whereIn('category_id', $this->treeIds($category))
                ->with($this->defaultRelations()),
            $limit
        );
    }

    /**
     * Paginate posts under region tree.
     */
    public function postsByRegion(Region $region, int $limit = self::PAGINATION_LIMIT): LengthAwarePaginator
    {
        return $this->paginatePosts(
            Post::published()
                ->whereHas('category', fn($q) => $q->doesntHave('children'))
                ->whereHas('regions', fn($q) => $q->whereIn('region_id', $this->treeIds($region)))
                ->with(['category', 'regions']),
            $limit
        );
    }

    /**
     * Paginate posts by archive year-month.
     */
    public function postsByDate(string $yearMonth, int $limit = self::PAGINATION_LIMIT): LengthAwarePaginator
    {
        ['year' => $year, 'month' => $month] = $this->parseDateSlug($yearMonth);

        return $this->paginatePosts(
            Post::published()
                ->when($year, fn($q) => $q->whereYear('published_at', $year))
                ->when($month, fn($q) => $q->whereMonth('published_at', $month)),
            $limit
        );
    }

    /**
     * Recent posts from same category tree.
     */
    public function recentPosts(Post $post, int $limit = self::RECENT_LIMIT): Collection
    {
        $root = $this->root($post->category);

        return $this->buildPostQuery($root, $post->id)
            ->limit($this->sanitizeLimit($limit))
            ->get();
    }

    /**
     * Posts by author.
     */
    public function postsByAuthor(string $slug, int $limit = self::PAGINATION_LIMIT): LengthAwarePaginator
    {
        $author = $this->resolveAuthor($slug);

        return $this->paginatePosts(
            Post::published()
                ->where('author_id', $author->id)
                ->with(['author', 'category']),
            $limit
        );
    }
}
