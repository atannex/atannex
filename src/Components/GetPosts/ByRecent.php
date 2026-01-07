<?php

namespace Atannex\Components\GetPosts;

use App\Models\Posts\Post;
use Illuminate\Support\Collection;

trait ByRecent
{
    /**
     * Fetch recent published posts that have an author, sorted and limited by the provided configuration.
     *
     * The method expects the `$config` array to contain the keys 'limit', 'sort', and 'order'.
     *
     * @param array $config Configuration options:
     *                      - 'limit' : int    Maximum number of posts to return.
     *                      - 'sort'  : string Column to sort by.
     *                      - 'order' : string Sorting direction ('asc' or 'desc').
     * @return Collection Collection of Post models matching the query.
     */
    public function getRecentPosts(array $config = []): Collection
    {
        $limit = $config['limit'];
        $sort  = $config['sort'];
        $order = $config['order'];

        return Post::query()
            ->published()
            ->whereHas('author')
            ->orderBy($sort, $order)
            ->limit($limit)
            ->get();
    }
}