<?php

namespace Lekeateh;

use App\Models\Posts\Post;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use DateTimeInterface;

trait GetMostViewedAndLikedPost
{
    /**
     * Retrieve posts with the highest combined views and likes within an optional date range.
     *
     * @param int $limit Maximum number of posts to retrieve.
     * @param DateTimeInterface|null $start Optional start date filter.
     * @param DateTimeInterface|null $end Optional end date filter.
     * @return Collection
     */
    public function getMostViewedAndLikedPosts(int $limit = 5, ?DateTimeInterface $start = null, ?DateTimeInterface $end = null): Collection
    {
        $start = $start ?? Carbon::now()->subDays(30); // Default to last 30 days
        $end = $end ?? Carbon::now();

        return Post::query()
            ->published()
            ->withCount('likes')
            ->withCount('views')
            ->orderByRaw('views_count + likes_count DESC')
            ->when($start, fn($query) => $query->where('published_at', '>=', $start))
            ->when($end, fn($query) => $query->where('published_at', '<=', $end))
            ->orderByDesc('published_at') // Secondary sort by publication date
            ->limit($limit)
            ->get();
    }
}