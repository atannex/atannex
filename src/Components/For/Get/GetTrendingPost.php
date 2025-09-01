<?php

namespace Atannex\Components\For\Get;

use InvalidArgumentException;
use Carbon\Carbon;
use App\Models\Posts\Post;
use Atannex\Traits\Metrics;
use Illuminate\Database\Eloquent\Collection;

trait GetTrendingPost
{
    use Metrics;

    /**
     * Retrieve trending posts with time-decay weighted engagement scores.
     *
     * @param array $config Configuration array with optional parameters:
     *                      - start: Carbon instance for start date (default: 7 days ago)
     *                      - end: Carbon instance for end date (default: now)
     *                      - limit: Number of posts to return (default: 10)
     *                      - weights: Engagement metric weights (default: from EngagementMetrics)
     *                      - min_score: Minimum engagement score threshold (default: 0)
     *                      - decay_factor: Multiplier for time decay (default: 0.1)
     * @throws InvalidArgumentException
     */
    public function getTrendingPosts(array $config = []): Collection
    {
        $defaultConfig = [
            'start' => Carbon::now()->subDays(7),
            'end' => Carbon::now(),
            'limit' => 10,
            'weights' => [],
            'min_score' => 0,
            'decay_factor' => 0.1,
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
        )->each(function ($post) use ($config) {
            $daysOld = $post->published_at->diffInDays($config['end']);
            $decay = exp(-$config['decay_factor'] * $daysOld);
            $post->trending_score = $post->engagement_score * $decay;
        });

        return $scoredPosts
            ->filter(fn($post) => $post->trending_score >= $config['min_score'])
            ->sortByDesc('trending_score')
            ->take($config['limit'])
            ->values();
    }
}
