<?php

declare(strict_types=1);

namespace Atannex\Components\Sections;

use App\Models\Posts\Post;
use Illuminate\Support\Collection;

trait HasBreaking
{
    /**
     * Retrieve multiple active breaking posts.
     *
     * - Returns posts marked as breaking
     * - Only includes posts that are published and flagged as PUBLISHED
     * - Sorted by latest `breaking_at`
     * - Supports optional limit (default 10)
     *
     * @param int $limit Maximum number of breaking posts to return
     * @return Collection<Post>
     */
    public function hasBreakingPosts(int $limit = 10): Collection
    {
        return Post::breaking()
            ->published()
            ->latest('breaking_at')
            ->take($limit)
            ->get();
    }
}
