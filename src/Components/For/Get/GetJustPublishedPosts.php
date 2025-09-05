<?php

namespace Atannex\Components\For\Get;

use App\Models\Posts\Post;
use Carbon\Carbon;
use Illuminate\Support\Collection;

trait GetJustPublishedPosts
{
    public function getJustPublishedPosts(array $config = []): Collection
    {
        $start   = isset($config['start'])
            ? Carbon::parse($config['start'])
            : now()->subDay();

        $end     = isset($config['end'])
            ? Carbon::parse($config['end'])
            : now();

        $limit   = $config['limit'] ?? 5;
        $sortBy  = $config['sort'] ?? 'published_at';
        $sortDir = $config['order'] ?? 'desc';

        return Post::published()
            ->whereBetween('published_at', [$start, $end])
            ->orderBy($sortBy, $sortDir)
            ->limit($limit)
            ->get();
    }
}
