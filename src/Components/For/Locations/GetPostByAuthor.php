<?php

namespace Atannex\Components\For\Locations;

use App\Models\Posts\Post;
use Illuminate\Support\Collection;

trait GetPostByAuthor
{

    public function getPostByAuthor(array $config = []): Collection
    {
        $authorIds = (array) ($config['author_id'] ?? []);
        $limit     = $config['limit'] ?? 10;
        $sortBy  = $config['sort'] ?? 'published_at';
        $sortDir = $config['order'] ?? 'desc';

        if ($authorIds === []) {
            return collect();
        }

        return Post::published()
            ->whereIn('author_id', $authorIds)
            ->whereHas('author', fn($query) => $query->whereNotNull('id'))
            ->orderBy($sortBy, $sortDir)
            ->limit($limit)
            ->get();
    }
}
