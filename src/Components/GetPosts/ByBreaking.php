<?php

namespace Atannex\Components\GetPosts;

use App\Models\Posts\Post;
use Illuminate\Support\Collection;

trait ByBreaking
{
    /**
     * Retrieve published breaking posts ordered and limited per configuration.
     *
     * @param array $config Configuration options:
     *                      - 'limit': int Maximum number of posts to return.
     *                      - 'sort': string Column name to sort by.
     *                      - 'order': string Sorting direction, 'asc' or 'desc'.
     * @return Collection Collection of Post models matching the breaking and published criteria.
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