<?php

namespace Atannex\Components;

use App\Models\Posts\Post;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use DateTimeInterface;

trait GetMostViewedAndCommentedPost
{
    /**
     * Retrieve posts with the highest combined views and comments within an optional date range.
     *
     * @param int $limit Maximum number of posts to retrieve.
     * @param DateTimeInterface|null $start Optional start date filter.
     * @param DateTimeInterface|null $end Optional end date filter.
     */
    public function getMostViewedAndCommentedPosts(int $limit = 5, ?DateTimeInterface $start = null, ?DateTimeInterface $end = null): Collection
    {
        $start = $start ?? Carbon::now()->subDays(30); // Default to last 30 days
        $end = $end ?? Carbon::now();

        return Post::query()
            ->published()
            ->withCount('comments') // Assumes a 'comments' relationship exists
            ->when($start, fn($query) => $query->where('published_at', '>=', $start))
            ->when($end, fn($query) => $query->where('published_at', '<=', $end))
            ->orderByRaw('views_count + comments_count DESC') // Order by combined views and comments
            ->orderByDesc('published_at') // Secondary sort by publication date
            ->limit($limit)
            ->get();
    }
}
