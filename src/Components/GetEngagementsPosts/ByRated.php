<?php

namespace Atannex\Components\GetEngagementsPosts;

use App\Enums\Flag;
use App\Models\Posts\Post;
use Illuminate\Support\Collection;

trait ByRated
{
    /**
     * Retrieve top-rated posts.
     *
     * Fetches posts ordered by a specified engagement metric
     * in descending order. Supports optional filtering by category or tag,
     * customizable eager loading, and configurable limit.
     *
     * @param  array  $config  Optional configuration:
     *                         - 'limit' => int Number of posts to retrieve (default 5)
     *                         - 'orderBy' => string Column to sort by (default 'rating')
     *                         - 'category_id' => int Filter by category ID
     *                         - 'tag_id' => int Filter by tag ID
     *                         - 'with' => array Eager load relations (default ['category', 'tags'])
     * @return Collection<int, Post>
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
            ->flagged(Flag::PUBLISHED)
            ->when($categoryId, fn ($q) => $q->where('category_id', $categoryId))
            ->when($tagId, fn ($q) => $q->whereHas('tags', fn ($q) => $q->where('id', $tagId)))
            ->with($relations)
            ->orderByDesc($orderBy)
            ->limit($limit);

        return $query->get();
    }
}
