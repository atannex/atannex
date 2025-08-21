<?php

namespace Atannex\Components;

use App\Models\Posts\Post;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use DateTimeInterface;

trait GetTrendingPost
{
    /**
     * Retrieve trending posts based on weighted interactions within a date range.
     *
     * @param int $limit
     * @param DateTimeInterface|null $start
     * @param DateTimeInterface|null $end
     * @return Collection<Post>
     */
    public function getTrendingPosts(int $limit = 5, ?DateTimeInterface $start = null, ?DateTimeInterface $end = null): Collection
    {
        $start ??= Carbon::now()->subDays(7);
        $end ??= Carbon::now();

        $trendingScoreExpr = '(views_count * 1) + (likes_count * 5) + (comments_count * 10) + (shares_count * 20)';

        return Post::query()
            ->selectRaw("posts.*, {$trendingScoreExpr} as trending_score")
            ->published()
            ->whereBetween('published_at', [$start, $end])
            ->orderByDesc('trending_score')
            ->limit($limit)
            ->get();
    }
}
