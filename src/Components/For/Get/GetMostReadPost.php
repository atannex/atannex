<?php

namespace Atannex\Components\For\Get;

use App\Models\Posts\Post;
use Illuminate\Support\Collection;

trait GetMostReadPost
{

    public function getMostReadPost(array $config = []): Collection
    {
        $limit   = $config['limit'] ?? 10;
        $sortBy  = $config['sort'] ?? 'views_count';
        $sortDir = $config['order'] ?? 'desc';

        return Post::published()
            ->orderBy($sortBy, $sortDir)
            ->limit($limit)
            ->get();
    }
}
