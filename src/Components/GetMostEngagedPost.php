<?php

namespace Atannex\Components;

use App\Models\Posts\Post;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use DateTimeInterface;

trait GetMostEngagedPost
{
    /**
     * Retrieve posts with the highest engagement (comments + likes) within an optional date range.
     *
     * @param int $limit Maximum number of posts to retrieve.
     * @param DateTimeInterface|null $start Optional start date filter.
     * @param DateTimeInterface|null $end Optional end date filter.
     */
    public function getMostEngagedPosts(int $limit = 5, ?DateTimeInterface $start = null, ?DateTimeInterface $end = null): Collection
    {
        $start = $start ?? Carbon::now()->subDays(30); // Default to last 30 days
        $end = $end ?? Carbon::now();

        return Post::query()
            ->published()
            ->withCount(['comments', 'likes']) // Assumes 'comments' and 'likes' relationships exist
            ->when($start, fn($query) => $query->where('published_at', '>=', $start))
            ->when($end, fn($query) => $query->where('published_at', '<=', $end))
            ->orderByRaw('comments_count + likes_count DESC') // Order by engagement score
            ->orderByDesc('published_at') // Secondary sort by publication date
            ->limit($limit)
            ->get();
    }
}
