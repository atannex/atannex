<?php

namespace Atannex\Components\For\Locations;

use App\Models\Posts\Post;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

trait GetPostByTag
{
    public function getPostByTag(array $config = []): Collection
    {
        $tagIds   = (array) ($config['tag_id'] ?? []);
        $limit    = $config['limit'] ?? 10;
        $sortBy  = $config['sort'] ?? 'published_at';
        $sortDir = $config['order'] ?? 'desc';

        if ($tagIds === []) {
            return collect();
        }

        return Post::published()
            ->whereHas('tags', function (Builder $query) use ($tagIds) {
                $query->whereIn('tags.id', $tagIds);
            })
            ->orderBy($sortBy, $sortDir)
            ->limit($limit)
            ->get();
    }
}
