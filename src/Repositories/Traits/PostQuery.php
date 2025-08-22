<?php

namespace Atannex\Repositories\Traits;

use App\Models\User;
use App\Models\Posts\Post;
use App\Models\Pages\Category;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Trait for handling post-related database queries in a structured and reusable manner.
 */
trait PostQuery
{
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
        if (empty($slugPath)) {
            return $this->emptyPaginator($limit);
        }

        $author = User::where('slug', $slugPath)->first();

        if (!$author) {
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
        $categoryIds = $category ? $this->getCategoryTreeIds($category) : collect();

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

    /* -----------------------------------------------------------------
     |  Private helpers
     | -----------------------------------------------------------------
     */

    /**
     * Default eager-load relations for posts.
     */
    private function defaultRelations(): array
    {
        return ['category', 'tags', 'author'];
    }

    /**
     * Closure for eager-loading authors with post counts.
     */
    private function authorWithPostCount(): \Closure
    {
        return fn($query) => $query->withCount('posts');
    }

    /**
     * Empty post query builder (always returns no results).
     */
    private function emptyPostQuery(): Builder
    {
        return Post::whereRaw('1 = 0')
            ->published()
            ->with($this->defaultRelations());
    }

    /**
     * Empty paginator for safe return when no results.
     */
    private function emptyPaginator(int $limit): LengthAwarePaginator
    {
        return new LengthAwarePaginator(collect(), 0, $this->sanitizeLimit($limit));
    }
}
