<?php

namespace Atannex\Components\GetEngagementsPosts;

use App\Enums\Flag;
use App\Models\Posts\Post;
use Illuminate\Support\Collection;

trait ByCommented
{
    /**
     * Get posts ordered by comment count in descending order.
     *
     * Supports optional filtering by category or tag and eager loading of relations via the `$config` array.
     *
     * @param array $config Optional configuration keys:
     *                      - 'limit' => int Number of posts to retrieve.
     *                      - 'category_id' => int Filter by category ID.
     *                      - 'tag_id' => int Filter by tag ID.
     *                      - 'with' => array Relations to eager load.
     * @return Collection<int, Post> The collection of Post models ordered by comment count (descending).
     */
    public function getMostCommentedPosts(array $config = []): Collection
    {
        $limit = $config['limit'];
        $categoryId = $config['category_id'];
        $tagId = $config['tag_id'];
        $relations = $config['with'];

        $query = Post::query()
            ->published()
            ->when($categoryId, fn ($q) => $q->where('category_id', $categoryId))
            ->when($tagId, fn ($q) => $q->whereHas('tags', fn ($q) => $q->where('id', $tagId)))
            ->with($relations)
            ->orderByDesc('comments_count')
            ->limit($limit);

        return $query->get();
    }
}