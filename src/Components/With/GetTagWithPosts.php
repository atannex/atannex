<?php

namespace Atannex\Components\With;

use App\Models\Tags\Tag;
use Illuminate\Support\Collection;

/**
 * Trait GetTagWithPosts
 *
 * Provides a method to retrieve tags along with their latest posts.
 */
trait GetTagWithPosts
{
    /**
     * Retrieve tags with their associated posts.
     *
     * @param array{
     *     tag_id: array<int>,
     *     limit?: int,
     *     post_limit?: int
     * } $config
     *
     * @return Collection<int, Tag>
     */
    public function getTagWithPosts(array $config): Collection
    {
        $tagIds     = $config['tag_id'] ?? [];
        $limit      = $config['limit'] ?? 5;
        $postLimit  = $config['post_limit'] ?? 5;

        if (empty($tagIds)) {
            return collect();
        }

        return Tag::with([
            'posts' => function ($query) use ($postLimit) {
                $query->latest('created_at')->take($postLimit);
            },
        ])
            ->whereIn('id', $tagIds)
            ->latest('created_at')
            ->take($limit)
            ->get();
    }
}
