<?php

namespace Atannex\Repositories\Traits;

use App\Models\Posts\Post;
use App\Models\Regions\Category;
use App\Models\Regions\Region;
use App\Models\Regions\Employee;
use Atannex\Concerns\HasResolver;
use Atannex\Traits\HasTree;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

trait PostQuery
{
    use HasResolver;
    use HasTree;

    protected const PAGINATION_LIMIT = 15;
    protected const RECENT_LIMIT = 5;

    /**
     * Retrieve paginated published posts that belong to the given category and its descendant categories.
     *
     * @param Category $category The root category whose subtree will be included in the query.
     * @param int $limit The number of posts per page.
     * @return \Illuminate\Pagination\LengthAwarePaginator A paginator containing published posts from the category subtree with content relations loaded.
     */
    public function postsByCategory(
        Category $category,
        int $limit = self::PAGINATION_LIMIT
    ): LengthAwarePaginator {
        return Post::query()
            ->published()
            ->whereIn('category_id', $this->getTreeIds($category))
            ->with($this->contentRelations())
            ->latest()
            ->paginate($limit);
    }

    /**
     * Fetches recently published posts, optionally limited to the same category tree as a given post.
     *
     * When $post is provided, results are restricted to posts whose category is in the root category's subtree of the given post and the given post itself is excluded.
     *
     * @param Post|null $post Optional post whose category tree will be used to restrict results.
     * @param int $limit Maximum number of posts to return.
     * @return \Illuminate\Support\Collection A collection of published Post models ordered by `published_at` descending.
     */
    public function recentPosts(
        ?Post $post = null,
        int $limit = self::RECENT_LIMIT
    ): Collection {
        $query = Post::query()
            ->published()
            ->with($this->contentRelations())
            ->latest('published_at')
            ->limit($this->sanitizeLimit($limit));

        if ($post) {
            $query
                ->whereIn(
                    'category_id',
                    $this->getTreeIds($this->getRoot($post->category))
                )
                ->where('id', '!=', $post->id);
        }

        return $query->get();
    }


    /**
     * Retrieve paginated published posts associated with the given region and its subtree.
     *
     * @param Region $region The root region whose subtree will be used to match post regions.
     * @param int $limit The number of posts per page.
     * @return LengthAwarePaginator A paginator of published posts with content relations eager loaded.
     */
    public function postsByRegion(Region $region, int $limit = self::PAGINATION_LIMIT): LengthAwarePaginator
    {
        $regionIds = $region->getSelfAndDescendantIds();
        return Post::query()
            ->published()
            ->whereIn('region_id', $regionIds)
            ->with(['author', 'category', 'tags'])
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
    }

    /**
     * Retrieve paginated published posts filtered by archive year and/or month.
     *
     * The $yearMonth string is parsed to extract year and optional month; when provided,
     * the query is restricted to posts published in that year and/or month. The result
     * includes content relations.
     *
     * @param string $yearMonth Archive string in `YYYY` or `YYYY-MM` format used to filter posts.
     * @param int $limit Maximum number of posts per page.
     * @return \Illuminate\Pagination\LengthAwarePaginator Paginated published posts matching the year/month filter with content relations loaded.
     */
    public function postsByDate(
        string $yearMonth,
        int $limit = self::PAGINATION_LIMIT
    ): LengthAwarePaginator {
        [$year, $month] = $this->extractDateParts($yearMonth);

        $query = Post::query()
            ->published()
            ->when(
                $year,
                fn($q) => $q->whereYear('published_at', $year)
            )
            ->when(
                $month,
                fn($q) => $q->whereMonth('published_at', $month)
            )
            ->with(['author', 'category', 'tags']);

        return $this->paginate($query, $limit);
    }

    /**
     * Retrieve paginated published posts for the author identified by the given user slug.
     *
     * Searches for an Employee whose related user record has the provided slug, aborts with 404 if not found,
     * and returns the author's published posts with content relations loaded, ordered by the model's default ordering.
     *
     * @param string $slug The user slug that identifies the author.
     * @param int $limit The number of posts per page.
     * @return \Illuminate\Pagination\LengthAwarePaginator A paginator of published Post models with their content relations loaded.
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If no author exists with the given slug.
     */
    public function postsByAuthor(
        string $slug,
        int $limit = self::PAGINATION_LIMIT
    ): LengthAwarePaginator {
        abort_if(! $this->authorExists($slug), 404);

        $authorId = Employee::whereHas(
            'user',
            fn($q) => $q->where('slug', $slug)
        )->value('id');

        abort_if(! $authorId, 404);

        return $this->paginate(
            Post::query()
                ->published()
                ->where('author_id', $authorId)
                ->with(['author', 'category', 'tags']),
            $limit
        );
    }
}
