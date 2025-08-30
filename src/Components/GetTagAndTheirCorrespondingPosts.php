<?php

namespace Atannex\Components;

use App\Models\Tags\Tag;
use Illuminate\Support\Collection;

trait GetTagAndTheirCorrespondingPosts
{

    public function getTagAndTheirCorrespondingPosts(array $config): Collection
    {
        $tagIds = $config['tag_id'];
        $limit = $config['limit'] ?? 5;
        $post_limit = $config['post_limit'] ?? 5;

        return Tag::with(['posts' => function ($query) use ($post_limit) {
            $query->latest('created_at')->take($post_limit);
        }])
            ->whereIn('id', $tagIds)
            ->latest('created_at')
            ->take($limit)
            ->get();
    }
}
