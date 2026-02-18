<?php

declare(strict_types=1);

namespace Atannex\Components\Sections;

use App\Models\Posts\Post;
use Illuminate\Support\Collection;

trait HasBreaking
{
    /**
     * Retrieve up to the given number of published posts marked as breaking, ordered by most recent `breaking_at`.
     *
     * @param  int  $limit  Maximum number of breaking posts to return (default 10).
     * @return Collection<Post> Collection of breaking Post models.
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
