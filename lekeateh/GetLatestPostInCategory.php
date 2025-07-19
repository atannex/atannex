<?php

namespace Lekeateh;

use App\Models\Posts\Post;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use DateTimeInterface;

trait GetLatestPostInCategory
{
    /**
     * Retrieve the latest posts in a specific category within an optional date range.
     *
     * @param int $categoryId The ID of the category to filter posts.
     * @param int $limit Maximum number of posts to retrieve.
     * @param DateTimeInterface|null $start Optional start date filter.
     * @param DateTimeInterface|null $end Optional end date filter.
     * @return Collection
     */
    public function getLatestPostsInCategory(int $categoryId, int $limit = 5, ?DateTimeInterface $start = null, ?DateTimeInterface $end = null): Collection
    {
        $start = $start ?? Carbon::now()->subDays(30); // Default to last 30 days
        $end = $end ?? Carbon::now();

        return Post::query()
            ->published()
            ->where('category_id', $categoryId)
            ->when($start, fn($query) => $query->where('published_at', '>=', $start))
            ->when($end, fn($query) => $query->where('published_at', '<=', $end))
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get();
    }
}
