<?php

namespace Atannex\Sections;

use App\Enums\Flag;
use App\Models\Posts\Post;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;

trait ByPastWeek
{
    /**
     * Get posts from the past 7 days,
     * ordered strictly by highest comment count to least.
     */
    public function getPastWeekPosts(int $limit = 5): Collection
    {
        $user = Auth::user();

        $timezone = $user->timezone ?? config('app.timezone', 'UTC');

        $startUtc = Date::now($timezone)
            ->subDays(6)
            ->startOfDay()
            ->setTimezone('UTC');

        $endUtc = Date::now($timezone)
            ->endOfDay()
            ->setTimezone('UTC');

        $query = Post::query()
            ->published()
            ->flagged(Flag::PUBLISHED)
            ->whereBetween('published_at', [$startUtc, $endUtc])
            ->withCount('comments')
            ->orderByDesc('comments_count');

        if ($limit > 0) {
            $query->limit($limit);
        }

        return $query->get();
    }
}
