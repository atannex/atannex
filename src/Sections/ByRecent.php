<?php

declare(strict_types=1);

namespace Atannex\Sections;

use App\Enums\Flag;
use App\Models\Posts\Post;
use Illuminate\Support\Collection;

/**
 * Trait ByRecent
 *
 * Provides functionality to retrieve recent published posts.
 */
trait ByRecent
{
    /**
     * Retrieve the most recent published posts.
     *
     * @param int $limit Number of posts to retrieve.
     *
     * @return Collection<int, Post>
     */
    public function getRecentPosts(int $limit = 5): Collection
    {
        return Post::query()
            ->flagged(Flag::PUBLISHED)
            ->latest('published_at')
            ->take($limit)
            ->get();
    }
}
