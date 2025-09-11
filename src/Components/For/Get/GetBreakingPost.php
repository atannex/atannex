<?php

namespace Atannex\Components\For\Get;

use App\Models\Posts\Post;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

trait GetBreakingPost
{
    /**
     * Retrieve breaking posts with optional sorting, limit, and user timezone.
     *
     * @param array $config Optional configuration:
     *                      - 'limit'    : Maximum number of posts to return (default: 5)
     *                      - 'sort'     : Column to sort by (default: 'published_at')
     *                      - 'order'    : Sorting direction 'asc' or 'desc' (default: 'desc')
     *                      - 'timezone' : User timezone (default: app timezone)
     *                      - 'min_priority' : Minimum priority to filter breaking posts
     * @return Collection Returns a collection of breaking posts.
     */
    public function getBreakingPosts(array $config = []): Collection
    {
        $limit        = $config['limit'] ?? 5;
        $sortBy       = $config['sort'] ?? 'published_at';
        $sortDir      = strtolower($config['order'] ?? 'desc') === 'asc' ? 'asc' : 'desc';
        $timezone     = $config['timezone'] ?? Auth::user()->timezone ?? config('app.timezone');
        $minPriority  = $config['min_priority'] ?? null;

        return Post::activeBreaking($timezone, $minPriority)
            ->orderBy($sortBy, $sortDir)
            ->limit($limit)
            ->get();
    }
}
