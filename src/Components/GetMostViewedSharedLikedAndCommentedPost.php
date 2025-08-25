<?php

namespace Atannex\Components;

use App\Models\Posts\Post;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use DateTimeInterface;

trait GetMostViewedSharedLikedAndCommentedPost
{
    /**
     * Retrieve posts with the highest combined views, shares, likes, and comments within an optional date range.
     *
     * @param int $limit Maximum number of posts to retrieve.
     * @param DateTimeInterface|null $start Optional start date filter.
     * @param DateTimeInterface|null $end Optional end date filter.
     */
    public function getMostViewedSharedLikedAndCommentedPosts(int $limit = 5, ?DateTimeInterface $start = null, ?DateTimeInterface $end = null): Collection
    {
        $start = $start ?? Carbon::now()->subDays(30);
        $end = $end ?? Carbon::now();

        return Post::query()
            ->published()
            ->withCount(['views', 'shares', 'likes', 'comments'])
            ->when($start, fn($query) => $query->where('published_at', '>=', $start))
            ->when($end, fn($query) => $query->where('published_at', '<=', $end))
            ->orderByRaw('views_count + shares_count + likes_count + comments_count DESC')
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get();
    }
}
