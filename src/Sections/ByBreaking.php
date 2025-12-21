<?php

namespace Atannex\Sections;

use App\Enums\Flag;
use App\Models\Posts\Post;
use Illuminate\Support\Collection;

trait ByBreaking
{
    public function getBreakingPosts(int $limit = 5): Collection
    {
        return Post::query()
            ->published()
            ->flagged(Flag::PUBLISHED)
            ->take($limit)
            ->get();
    }
}
