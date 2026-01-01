<?php

declare(strict_types=1);

namespace Atannex\Components\Sections;

use App\Models\Posts\Post;
use Illuminate\Support\Collection;

/**
 * Trait HasRecent
 *
 * Provides functionality to retrieve recent published posts.
 */
trait HasRecent
{
    /**
     * Retrieve the most recent published posts.
     *
     * @param int $limit Number of posts to retrieve.
     *
     * @return Collection<int, Post>
     */
    public function hasRecentPosts(int $limit = 5): Collection
    {
        return Post::query()
            ->published()
            ->latest('published_at')
            ->take($limit)
            ->get();
    }
}
