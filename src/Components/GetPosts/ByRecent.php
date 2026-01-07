<?php

namespace Atannex\Components\GetPosts;

use App\Models\Posts\Post;
use Illuminate\Support\Collection;

trait ByRecent
{
    /**
     * Retrieve recent posts with configurable sorting and limit.
     *
     * @param  array  $config  Configuration options:
     *                         - 'limit' : int    Maximum number of posts to return (default: 10)
     *                         - 'sort'  : string Column to sort by (default: 'published_at')
     *                         - 'order' : string Sorting direction 'asc' or 'desc' (default: 'desc')
     * @return Collection
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
