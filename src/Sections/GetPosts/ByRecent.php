<?php

namespace Atannex\Sections\GetPosts;

use App\Models\Posts\Post;
use Illuminate\Support\Collection;

/**
 * Trait GetRecentPost
 *
 * Provides functionality to retrieve recent published posts.
 */
trait ByRecent
{
    /**
     * Retrieve the most recent published posts.
     *
     * @param int $limit The number of posts to retrieve.
     */
    public function getRecentPosts(int $limit = 5): Collection
    {
        return Post::published()
            ->latest('published_at')
            ->limit($limit)
            ->get();
    }
}
