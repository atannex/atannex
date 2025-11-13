<?php

namespace Atannex\Components\GetEngagementsPosts;

use App\Models\Posts\Post;
use Illuminate\Support\Collection;

trait ByViewed
{
    /**
     * Retrieve the most viewed posts.
     *
     * Fetches posts ordered by view count in descending order.
     * Supports optional filtering by category or tag, eager loading,
     * and configurable limit.
     *
     * @param  array  $config  Optional configuration:
     *                         - 'limit' => int Number of posts to retrieve (default 5)
     *                         - 'category_id' => int Filter by category ID
     *                         - 'tag_id' => int Filter by tag ID
     *                         - 'with' => array Eager load relations (default ['category', 'tags'])
     * @return Collection<int, Post>
     */
    public function getMostViewedPosts(array $config = []): Collection
    {
        $limit = $config['limit'] ?? 5;
        $categoryId = $config['category_id'] ?? null;
        $tagId = $config['tag_id'] ?? null;
        $relations = $config['with'] ?? ['category', 'tags'];

        $query = Post::query()
            ->published()
            ->when($categoryId, fn ($q) => $q->where('category_id', $categoryId))
            ->when($tagId, fn ($q) => $q->whereHas('tags', fn ($q) => $q->where('id', $tagId)))
            ->with($relations)
            ->orderByDesc('views')
            ->limit($limit);

        return $query->get();
    }
}
