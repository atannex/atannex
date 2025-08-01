<?php

namespace Atangageih\Repositories\Traits;

use App\Models\User;
use App\Models\Posts\Post;
use App\Models\Pages\Category;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Atangageih\Services\Traits\Helper;

/**
 * Trait for handling post-related database queries in a structured and reusable manner.
 */
trait PostQuery
{
    use Helper;

    /**
     * Retrieve paginated posts for a given category.
     *
     * @param Category|null $category The category to filter posts by.
     * @param int $limit Number of posts per page (default: 15).
     * @return LengthAwarePaginator Paginated posts collection.
     * @throws \InvalidArgumentException If category is null.
     */
    public function getPostsByCategory(?Category $category, int $limit = self::DEFAULT_PAGINATION_LIMIT): LengthAwarePaginator
    {
        return $this->buildPostQuery($category)
            ->latest()
            ->paginate(max(1, $limit));
    }

    /**
     * Retrieve recent posts for a given post's category, excluding the post itself.
     *
     * @param Post|null $post The post to base the category query on.
     * @param int $limit Number of recent posts to retrieve (default: 5).
     * @return Collection Collection of recent posts.
     */
    public function getRecentPosts(?Post $post, int $limit = self::DEFAULT_RECENT_POSTS_LIMIT): Collection
    {
        if (is_null($post) || is_null($post->category)) {
            return collect();
        }

        $category = $this->getRootCategory($post->category);
        if (is_null($category)) {
            return collect();
        }

        return $this->buildPostQuery($category, $post->id)
            ->latest()
            ->take(max(1, $limit))
            ->get();
    }

    /**
     * Retrieve paginated posts by an author identified by their slug.
     *
     * @param string|null $slugPath The author's unique slug.
     * @param int $limit Number of posts per page (default: 15).
     * @return LengthAwarePaginator Paginated posts collection.
     */
    public function getPostsByAuthor(?string $slugPath, int $limit = self::DEFAULT_PAGINATION_LIMIT): LengthAwarePaginator
    {
        if (empty($slugPath)) {
            return new LengthAwarePaginator(collect(), 0, max(1, $limit));
        }

        $author = User::where('slug', $slugPath)->first();

        if (is_null($author)) {
            return new LengthAwarePaginator(collect(), 0, max(1, $limit));
        }

        return Post::with([
            'author' => fn($query) => $query->withCount('posts'),
            'category'
        ])
            ->where('author_id', $author->id)
            ->published()
            ->orderByDesc('created_at')
            ->paginate(max(1, $limit))
            ->appends(['slug' => $slugPath]);
    }

    /**
     * Build a query for published posts within a category tree.
     *
     * @param Category|null $category The root category for the query.
     * @param int|null $excludeId Optional post ID to exclude from results.
     * @return Builder The constructed query builder instance.
     * @throws \InvalidArgumentException If category is null.
     */
    protected function buildPostQuery(?Category $category, ?int $excludeId = null): Builder
    {
        $categoryIds = $this->getCategoryTreeIds($category);
        if (empty($categoryIds)) {
            return Post::whereRaw('1 = 0')->published()->with(['category', 'tags', 'author']);
        }

        $query = Post::published()
            ->whereIn('category_id', $categoryIds)
            ->with(['category', 'tags', 'author']);

        if (!is_null($excludeId)) {
            $query->where('id', '!=', $excludeId);
        }

        return $query;
    }
}
