<?php

namespace Atannex\Components\GetPosts;

use App\Models\Posts\Post;
use Illuminate\Support\Collection;

trait ByBreaking
{
    /**
     * Get breaking posts that are published, ordered and limited according to the provided configuration.
     *
     * @param array $config {
     *     Configuration options:
     *     @type int    $limit Maximum number of posts to return.
     *     @type string $sort  Column name to sort by.
     *     @type string $order Sorting direction, 'asc' or 'desc'.
     * }
     * @return Collection Collection of Post models that are breaking and published.
     */
    public function getBreakingPosts(array $config): Collection
    {
        return Post::breaking()
            ->published()
            ->orderBy($config['sort'], $config['order'])
            ->limit($config['limit'])
            ->get();
    }
}