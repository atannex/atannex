<?php

namespace Atannex\Components;

use App\Models\Posts\Post;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use DateTimeInterface;

trait GetHotPicksPost
{
    /**
     * Retrieve posts marked as hot picks within an optional date range.
     *
     * @param int $limit Maximum number of posts to retrieve.
     * @param DateTimeInterface|null $start Optional start date filter.
     * @param DateTimeInterface|null $end Optional end date filter.
     */
    public function getHotPicksPosts(int $limit = 5, ?DateTimeInterface $start = null, ?DateTimeInterface $end = null): Collection
    {
        $start = $start ?? Carbon::now()->subDays(7); // Default to last 7 days
        $end = $end ?? Carbon::now();

        return Post::query()
            ->published()
            ->where('is_hot_pick', true)
            ->when($start, fn($query) => $query->where('published_at', '>=', $start))
            ->when($end, fn($query) => $query->where('published_at', '<=', $end))
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get();
    }
}
