<?php

namespace Atannex\Components\GetPosts;

use App\Models\Posts\Post;
use Illuminate\Support\Collection;

trait ByBreaking
{
    public function getBreakingPosts(array $config): Collection
    {
        return Post::breaking()
            ->published()
            ->orderBy($config['sort'], $config['order'])
            ->limit($config['limit'])
            ->get();
    }
}
