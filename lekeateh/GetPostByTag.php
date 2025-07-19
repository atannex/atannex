<?php

namespace Lekeateh;

use App\Models\Posts\Post;
use Illuminate\Support\Collection;

trait GetPostByTag
{
    public function getPostByTag(array $config): Collection
    {
        return Post::query()
            ->published()
            ->whereHas('tags', function ($query) use ($config) {
                $query->where('tags.id', $config['tag_id']);
            })
            ->latest()
            ->limit($config['limit'])
            ->get();
    }
}
