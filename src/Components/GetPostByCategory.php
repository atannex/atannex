<?php

namespace Atannex\Components;

use App\Models\Posts\Post;
use Illuminate\Support\Collection;

trait GetPostByCategory
{
    public function getPostByCategory(array $config): Collection
    {
        return Post::query()
            ->published(false)
            ->whereIn('category_id', (array) $config['category_id'])
            ->latest()
            ->limit($config['limit'])
            ->get();
    }
}
