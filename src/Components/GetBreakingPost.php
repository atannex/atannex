<?php

namespace Atannex\Components;

use App\Models\Posts\Post;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Arr;

trait GetBreakingPost
{
    /**
     * Get breaking posts within the last X hours.
     *
     * @param array $config
     *      - 'limit': int, number of posts to return
     *      - 'hours': int, number of past hours to fetch posts from (optional)
     *
     * @return Collection
     */
    public function getBreakingPosts(array $config): Collection
    {
        $hours = Arr::get($config, 'hours', 3);
        $limit = Arr::get($config, 'limit', 5);

        $start = Carbon::now()->subHours($hours);
        $end = Carbon::now();

        return Post::published()
            ->isBreaking()
            ->whereBetween('published_at', [$start, $end])
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get();
    }
}
