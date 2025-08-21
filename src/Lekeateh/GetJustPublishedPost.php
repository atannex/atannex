<?php

namespace Lekeateh;

use App\Models\Posts\Post;
use Carbon\Carbon;
use Illuminate\Support\Collection;

trait GetJustPublishedPost
{

    public function getJustPublishedPosts(array $config): Collection
    {
        $start = Carbon::now()->subHours(24);
        $end = Carbon::now();

        return Post::query()
            ->published()
            ->when($start, fn($query) => $query->where('published_at', '>=', $start))
            ->when($end, fn($query) => $query->where('published_at', '<=', $end))
            ->orderByDesc('published_at')
            ->limit($config['limit'] ?? 5)
            ->get();
    }
}
