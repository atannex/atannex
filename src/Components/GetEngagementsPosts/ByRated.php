<?php

namespace Atannex\Components\GetEngagementsPosts;

use App\Enums\Flag;
use App\Models\Posts\Post;
use Illuminate\Support\Collection;

trait ByRated
{
    /**
     * Retrieve top-rated posts filtered and ordered by an engagement metric.
     *
     * Supports optional filtering by category_id and tag_id, configurable eager loading,
     * ordering by a specified column, and limiting the number of results.
     *
     * @param array $config Optional configuration:
     *                      - 'limit' (int): Number of posts to retrieve (default 5)
     *                      - 'orderBy' (string): Column to sort by (default 'rating')
     *                      - 'category_id' (int|null): Filter by category ID
     *                      - 'tag_id' (int|null): Filter by tag ID
     *                      - 'with' (array): Relations to eager load (default ['category', 'tags'])
     * @return Collection<int, Post> A collection of Post models matching the criteria, ordered descending by the configured engagement column.
     */
    public function getTopRatedPosts(array $config = []): Collection
    {
        $limit = $config['limit'];
        $orderBy = $config['orderBy'];
        $categoryId = $config['category_id'];
        $tagId = $config['tag_id'];
        $relations = $config['with'];

        $query = Post::query()
            ->published()
            ->when($categoryId, fn ($q) => $q->where('category_id', $categoryId))
            ->when($tagId, fn ($q) => $q->whereHas('tags', fn ($q) => $q->where('id', $tagId)))
            ->with($relations)
            ->orderByDesc($orderBy)
            ->limit($limit);

        return $query->get();
    }
}