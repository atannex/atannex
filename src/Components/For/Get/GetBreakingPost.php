<?php

namespace Atannex\Components\For\Get;

use App\Models\Posts\Post;
use Illuminate\Support\Collection;

trait GetBreakingPost
{
    /**
     * Retrieve breaking posts within a specified date range with optional sorting and limit.
     *
     * @param array $config Optional configuration:
     *                      - 'start' : Start date as a Carbon instance (default: 1 day ago)
     *                      - 'end'   : End date as a Carbon instance (default: now)
     *                      - 'limit' : Maximum number of posts to return (default: 5)
     *                      - 'sort'  : Column to sort by (default: 'breaking_until')
     *                      - 'order' : Sorting direction 'asc' or 'desc' (default: 'desc')
     * @return Collection Returns a collection of breaking posts.
     */
    public function getBreakingPosts(array $config = []): Collection
    {
        $limit   = $config['limit'] ?? 5;
        $sortBy  = $config['sort'] ?? 'breaking_until';
        $sortDir = $config['order'] ?? 'desc';

        return Post::published()
            ->activeBreaking()
            ->orderBy($sortBy, $sortDir)
            ->limit($limit)
            ->get();
    }
}

