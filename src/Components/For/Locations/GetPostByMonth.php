<?php

namespace Atannex\Components\For\Locations;

use App\Models\Posts\Post;
use Illuminate\Support\Collection;
use Carbon\Carbon;

trait GetPostByMonth
{
    public function getPostByMonth(array $config = []): Collection
    {
        $month   = $config['month'] ?? now()->month;
        $year    = $config['year'] ?? now()->year;
        $limit   = $config['limit'] ?? 10;
        $sortBy  = $config['sort'] ?? 'published_at';
        $sortDir = $config['order'] ?? 'desc';

        if (is_string($month)) {
            $month = Carbon::parse('1 ' . $month)->month;
        }

        return Post::published()
            ->whereYear('published_at', $year)
            ->whereMonth('published_at', $month)
            ->orderBy($sortBy, $sortDir)
            ->limit($limit)
            ->get();
    }
}
