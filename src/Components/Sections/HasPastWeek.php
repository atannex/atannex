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
     * Get posts from the past 7 days,
     * ordered strictly by highest comment count to least.
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
