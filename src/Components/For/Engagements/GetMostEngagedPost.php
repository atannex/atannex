<?php

namespace Atannex\Components\For\Engagements;

use InvalidArgumentException;
use App\Models\Posts\Post;
use Illuminate\Support\Collection;

trait GetMostEngagedPost
{
    /**
     * Retrieve posts sorted by a specified engagement metric.
     *
     * Supports filtering by category or tag, configurable limit,
     * and eager loading of relations.
     *
     * @param string $metric The engagement metric to sort by
     *                       e.g., 'rating', 'views', 'shares', 'likes', 'comments_count'
     * @param array $config Optional configuration:
     *                      - 'limit' => int Number of posts to retrieve (default 5)
     *                      - 'category_id' => int Filter by category ID
     *                      - 'tag_id' => int Filter by tag ID
     *                      - 'with' => array Eager load relations (default ['category', 'tags'])
     * @return Collection<int, Post>
     */
    public function getMostEngagedPosts(string $metric, array $config = []): Collection
    {
        $allowedMetrics = ['rating', 'views', 'shares', 'likes', 'comments_count'];
        if (!in_array($metric, $allowedMetrics)) {
            throw new InvalidArgumentException(sprintf("Invalid engagement metric '%s'. Allowed metrics: ", $metric) . implode(', ', $allowedMetrics));
        }

        $limit = $config['limit'] ?? 5;
        $categoryId = $config['category_id'] ?? null;
        $tagId = $config['tag_id'] ?? null;
        $relations = $config['with'] ?? ['category', 'tags'];

        $query = Post::query()
            ->published()
            ->when($categoryId, fn($q) => $q->where('category_id', $categoryId))
            ->when($tagId, fn($q) => $q->whereHas('tags', fn($q) => $q->where('id', $tagId)))
            ->with($relations)
            ->orderByDesc($metric)
            ->limit($limit);

        return $query->get();
    }
}
