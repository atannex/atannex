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
            ->take($limit)
            ->get();
    }
}
