<?php

namespace Atannex\Components\GetPosts;

use App\Enums\Flag;
use App\Models\Posts\Post;
use Illuminate\Support\Collection;

trait ByBreaking
{
    /**
     * Retrieve breaking posts with explicit sorting, limit, and user timezone.
     *
     * @param  array  $config  Configuration:
     *                         - 'limit'    : int    Maximum number of posts to return
     *                         - 'sort'     : string Column to sort by
     *                         - 'order'    : string Sorting direction 'asc' or 'desc'
     *                         - 'timezone' : string User timezone
     * @return Collection Returns a collection of breaking posts.
     */
    public function getBreakingPosts(array $config): Collection
    {
        return Post::activeBreaking($config['timezone'])
            ->flagged(Flag::PUBLISHED)
            ->orderBy($config['sort'], $config['order'])
            ->limit($config['limit'])
            ->get();
    }
}
