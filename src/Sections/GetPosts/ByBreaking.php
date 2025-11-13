<?php

namespace Atannex\Sections\GetPosts;

use App\Models\Posts\Post;
use Illuminate\Support\Collection;

trait ByBreaking
{
    public function getBreakingPosts(int $limit = 5): Collection
    {
        return Post::query()
            ->published()
            ->withCount(['views', 'likes', 'shares'])
            ->withAvg('ratings', 'rating')
            ->orderByRaw('(views_count + (likes_count * 2) + shares_count + (COALESCE(ratings_avg_rating, 0) * 3)) DESC')
            ->take($limit)
            ->get();
    }
}
