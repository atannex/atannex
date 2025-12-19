<?php

namespace Atannex\Sections;

use App\Enums\Flag;
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
            ->flagged(Flag::PUBLISHED)
            ->published()
            ->orderByDesc('views_count')
            ->take($limit)
            ->get();
    }
}
