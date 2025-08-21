<?php

namespace Atannex\Components;

use App\Models\Posts\Post;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

trait GetPostByFondom
{
    public function getPostByFondom(array $config): Collection
    {
        return Post::published()
            ->whereHas(
                'regions',
                fn(Builder $query) => $query
                    ->where('type', 'Fondom')
                    ->published()
            )
            ->limit($config['limit'] ?? 5)
            ->get();
    }
}
