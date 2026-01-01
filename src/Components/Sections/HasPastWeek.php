<?php

declare(strict_types=1);

namespace Atannex\Components\Sections;

use App\Models\Posts\Post;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;

trait HasPastWeek
{
    /**
     * Retrieve posts published within the past seven days ordered by comment count descending.
     *
     * If $limit is greater than 0, the result is limited to that many posts; if 0, no limit is applied.
     *
     * @param int $limit Number of posts to return; 0 to return all matching posts.
     * @return \Illuminate\Support\Collection Collection of Post models with a `comments_count` attribute, ordered by `comments_count` descending.
     */
    public function hasPastWeekPosts(int $limit = 5): Collection
    {
        $start = Date::now()->subDays(6)->startOfDay();
        $end = Date::now()->endOfDay();

        $query = Post::query()
            ->published()
            ->whereBetween('published_at', [$start, $end])
            ->withCount('comments')
            ->orderByDesc('comments_count');

        if ($limit > 0) {
            $query->limit($limit);
        }

        return $query->get();
    }
}