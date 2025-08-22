<?php

namespace Atannex\Sections;

use App\Models\Posts\Post;
use Illuminate\Support\Collection;

/**
 * Trait GetRecentPost
 *
 * Provides functionality to retrieve recent published posts.
 */
trait GetRecentPost
{
    /**
     * Retrieve the most recent published posts.
     *
     * @param int $limit The number of posts to retrieve.
     * @return \Illuminate\Support\Collection
     */
    public function getRecentPublishedPosts(int $limit = 5): Collection
    {
        return Post::published(false)
            ->latest('created_at')
            ->limit($limit)
            ->get();
    }
}
