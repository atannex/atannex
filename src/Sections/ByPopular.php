<?php

namespace Atannex\Sections;

use App\Enums\Flag;
use App\Models\Posts\Post;
use Illuminate\Support\Collection;

trait ByPopular
{
    public function getPopularPosts(int $limit = 5): Collection
    {
        return Post::query()
            ->published()
            ->flagged(Flag::PUBLISHED)
            ->withCount(['views', 'likes', 'shares'])
            ->withAvg('ratings', 'rating')
            ->orderByRaw('(views_count + (likes_count * 2) + shares_count + (COALESCE(ratings_avg_rating, 0) * 3)) DESC')
            ->take($limit)
            ->get();
    }
}
