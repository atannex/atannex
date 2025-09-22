<?php

namespace Atannex\Sections\GetPosts;

use Carbon\Carbon;
use App\Models\Posts\Post;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

trait ByToday
{
    /**
     * Get posts published today for the given user's timezone.
     */
    public function getTodayPosts(int $limit = 5): Collection
    {
        $user = Auth::user();

        $timezone = $user->timezone ?? config('app.timezone', 'UTC');

        $startOfDay = Carbon::now($timezone)->startOfDay()->setTimezone('UTC');
        $endOfDay   = Carbon::now($timezone)->endOfDay()->setTimezone('UTC');

        $query = Post::query()
            ->published()
            ->whereBetween('published_at', [$startOfDay, $endOfDay]);

        if ($limit > 0) {
            $query->limit($limit);
        }

        return $query->get();
    }
}
