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
     * @param  array $config Optional configuration:
     *                       - 'limit'    : int    Maximum number of posts to return (default: 5)
     *                       - 'sort'     : string Column to sort by (default: 'published_at')
     *                       - 'order'    : string Sorting direction 'asc' or 'desc' (default: 'desc')
     *                       - 'timezone' : string User timezone (default: user timezone or app timezone)
     * @return Collection Returns a collection of breaking posts.
     */
    public function getBreakingPosts(array $config = []): Collection
    {
        $defaults = [
            'limit'    => 5,
            'sort'     => 'published_at',
            'order'    => 'desc',
            'timezone' => Auth::user()->timezone ?? config('app.timezone'),
        ];

        $config = array_merge($defaults, $config);

        $sortDir = strtolower($config['order']) === 'asc' ? 'asc' : 'desc';

        return Post::activeBreaking($config['timezone'])
            ->orderBy($config['sort'], $sortDir)
            ->limit($config['limit'])
            ->get();
    }
}
