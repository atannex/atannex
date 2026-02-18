<?php

namespace Atannex\Components\GetPosts;

use App\Models\Posts\Post;
use Illuminate\Support\Collection;

trait ByRecent
{
    public function getRecentPosts(array $config): Collection
    {
        $limit = $config['limit'];
        $sort = $config['sort'];
        $order = $config['order'];

        return Post::query()
            ->published()
            ->whereHas('author')
            ->orderBy($sort, $order)
            ->limit($limit)
            ->get();
    }
}
