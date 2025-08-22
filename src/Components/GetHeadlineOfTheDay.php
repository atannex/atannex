<?php

namespace Atannex\Components;

use Carbon\Carbon;
use App\Models\Posts\Post;
use Atannex\Traits\EngagementMetrics;
use Illuminate\Database\Eloquent\Collection;

trait GetHeadlineOfTheDay
{
    use EngagementMetrics;

    /**
     * Retrieve headlines of the day based on engagement metrics, recency, and publication status.
     *
     * @param array $config Configuration array with optional parameters:
     *                      - start: Carbon instance for start date (default: start of current day)
     *                      - end: Carbon instance for end date (default: end of current day)
     *                      - limit: Number of posts to return (default: 5)
     *                      - weights: Engagement metric weights (default: from EngagementMetrics, optimized for headlines)
     *                      - min_score: Minimum engagement score threshold (default: 0)
     *                      - prioritize_recency: Weight engagement score by recency within the day (default: true)
     * @return Collection
     */
    public function getHeadlinesOfTheDay(array $config = []): Collection
    {
        $defaultConfig = [
            'start' => Carbon::today(),
            'end' => Carbon::today()->endOfDay(),
            'limit' => 5,
            'weights' => ['views' => 0.2, 'likes' => 0.2, 'comments' => 0.4, 'ratings' => 0.1, 'shares' => 0.1],
            'min_score' => 0,
            'prioritize_recency' => true,
        ];

        $config = array_merge($defaultConfig, $config);

        $posts = Post::published()
            ->whereBetween('published_at', [$config['start'], $config['end']])
            ->get();

        $scoredPosts = $this->calculateWeightedScore(
            $posts,
            $config['weights'],
            $config['start'],
            $config['end']
        );

        if ($config['prioritize_recency']) {
            $scoredPosts = $scoredPosts->each(function ($post) use ($config) {
                $hoursSincePublished = $post->published_at->diffInHours($config['end']);
                $recencyFactor = max(0.5, 1 - ($hoursSincePublished / 24));
                $post->headline_score = $post->engagement_score * $recencyFactor;
            });
            $sortKey = 'headline_score';
        } else {
            $sortKey = 'engagement_score';
        }

        return $scoredPosts
            ->filter(fn($post) => ($post->{$sortKey} ?? $post->engagement_score) >= $config['min_score'])
            ->sortByDesc($sortKey)
            ->take($config['limit'])
            ->values();
    }
}
