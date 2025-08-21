<?php

namespace Atannex\Components;

use App\Models\Posts\Post;
use Carbon\Carbon;
use Illuminate\Support\Collection;

trait GetBreakingPost
{

    public function getBreakingPosts(array $config): Collection
    {
        $start = Carbon::now()->subHours(3);
        $end = Carbon::now();

        return Post::query()
            ->published()
            ->isBreaking()
            ->when($start, fn($query) => $query->where('published_at', '>=', $start))
            ->when($end, fn($query) => $query->where('published_at', '<=', $end))
            ->orderByDesc('published_at')
            ->limit($config['limit'])
            ->get();
    }
}
