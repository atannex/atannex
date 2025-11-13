<?php

namespace Atannex\Sections\GetPosts;

use App\Models\Posts\Post;
use Illuminate\Support\Collection;

trait ByMostRead
{
    /**
     * Get the most-read posts based on the number of views.
     */
    public function getMostReadPosts(int $limit = 5): Collection
    {
        return Post::withCount('views')
            ->published()
            ->orderByDesc('views_count')
            ->take($limit)
            ->get();
    }
}
