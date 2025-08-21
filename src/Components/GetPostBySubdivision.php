<?php

namespace Atannex\Components;

use App\Models\Posts\Post;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

trait GetPostBySubdivision
{
    public function getPostBySubdivision(array $config): Collection
    {
        return Post::published()
            ->whereHas(
                'regions',
                fn(Builder $query) => $query
                    ->where('type', 'Sub-Division')
                    ->published()
            )
            ->limit($config['limit'] ?? 5)
            ->get();
    }
}
