<?php

namespace Atannex\Components;

use App\Models\Posts\Post;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use DateTimeInterface;

trait GetComingSoonPost
{
    /**
     * Retrieve upcoming posts marked as coming soon within an optional date range.
     *
     * @param int $limit Maximum number of posts to retrieve.
     * @param DateTimeInterface|null $start Optional start date filter (future date).
     * @param DateTimeInterface|null $end Optional end date filter (future date).
     * @return Collection
     */
    public function getComingSoonPosts(int $limit = 5, ?DateTimeInterface $start = null, ?DateTimeInterface $end = null): Collection
    {
        $start = $start ?? Carbon::now();
        $end = $end ?? Carbon::now()->addDays(7);

        return Post::query()
            ->isScheduled()
            ->where('published_at', '>', Carbon::now())
            ->when($start, fn($query) => $query->where('published_at', '>=', $start))
            ->when($end, fn($query) => $query->where('published_at', '<=', $end))
            ->orderBy('published_at', 'asc')
            ->limit($limit)
            ->get();
    }
}
