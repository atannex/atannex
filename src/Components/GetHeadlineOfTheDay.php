<?php

namespace Atannex\Components;

use App\Models\Posts\Post;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use DateTimeInterface;

trait GetHeadlineOfTheDay
{
    /**
     * Retrieve posts marked as headline of the day within an optional date range.
     *
     * @param int $limit Maximum number of posts to retrieve.
     * @param DateTimeInterface|null $start Optional start date filter.
     * @param DateTimeInterface|null $end Optional end date filter.
     * @return Collection
     */
    public function getHeadlinesOfTheDay(int $limit = 1, ?DateTimeInterface $start = null, ?DateTimeInterface $end = null): Collection
    {
        $start = $start ?? Carbon::today(); // Default to start of current day
        $end = $end ?? Carbon::today()->endOfDay(); // Default to end of current day

        return Post::query()
            ->published()
            ->where('is_headline', true)
            ->when($start, fn($query) => $query->where('published_at', '>=', $start))
            ->when($end, fn($query) => $query->where('published_at', '<=', $end))
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get();
    }
}
