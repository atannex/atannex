<?php

namespace Atannex\Sections;

use App\Enums\Flag;
use App\Models\Posts\Post;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;

trait ByToday
{
    /**
     * Get posts published today for the given user's timezone.
     */
    public function getTodayPosts(int $limit = 5): Collection
    {
        $user = Auth::user();

        $timezone = $user->timezone ?? config('app.timezone', 'UTC');

        $startOfDay = Date::now($timezone)->startOfDay()->setTimezone('UTC');
        $endOfDay = Date::now($timezone)->endOfDay()->setTimezone('UTC');

        $query = Post::query()
            ->published()
            ->flagged(Flag::PUBLISHED)
            ->whereBetween('published_at', [$startOfDay, $endOfDay]);

        if ($limit > 0) {
            $query->limit($limit);
        }

        return $query->get();
    }
}
