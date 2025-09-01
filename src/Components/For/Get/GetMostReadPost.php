<?php

namespace Atannex\Components\For\Get;

use App\Models\Posts\Post;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use DateTimeInterface;

trait GetMostReadPost
{
    /**
     * Retrieve posts with the highest number of views within an optional date range.
     *
     * @param int $limit Maximum number of posts to retrieve.
     * @param DateTimeInterface|null $start Optional start date filter.
     * @param DateTimeInterface|null $end Optional end date filter.
     */
    public function getMostReadPosts(int $limit = 5, ?DateTimeInterface $start = null, ?DateTimeInterface $end = null): Collection
    {
        $start = $start ?? Carbon::now()->subDays(30); // Default to last 30 days
        $end = $end ?? Carbon::now();

        return Post::query()
            ->published()
            ->when($start, fn($query) => $query->where('published_at', '>=', $start))
            ->when($end, fn($query) => $query->where('published_at', '<=', $end))
            ->orderByDesc('views_count') // Order by view count
            ->orderByDesc('published_at') // Secondary sort by publication date
            ->limit($limit)
            ->get();
    }
}
