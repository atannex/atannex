<?php

namespace Atannex\Components;

use Carbon\Carbon;
use App\Models\Posts\Post;
use Atannex\Traits\Metrics;
use Illuminate\Database\Eloquent\Collection;

trait GetHeadlineOfTheDay
{
    use Metrics;

    /**
     * Retrieve headlines of the day based on engagement metrics, recency, and publication status.
     *
     * @param array $config Optional configuration:
     *                      - start: Carbon instance or date string (default: today start)
     *                      - end: Carbon instance or date string (default: today end)
     *                      - limit: Number of posts to return
     *                      - weights: Engagement metric weights
     *                      - min_score: Minimum engagement score threshold
     *                      - prioritize_recency: Whether to boost recent posts
     * @return Collection
     */
    public function getHeadlinesOfTheDay(array $config = []): Collection
    {
        // Load default config from file
        $defaultConfig = config('editor_picks');

        // Determine dynamic start and end dates
        $start = $config['start'] ?? Carbon::today();
        $end   = $config['end'] ?? Carbon::today()->endOfDay();

        $start = $start instanceof Carbon ? $start : Carbon::parse($start);
        $end   = $end instanceof Carbon ? $end : Carbon::parse($end);

        // Merge defaults with user config, giving priority to user values
        $config = array_merge($defaultConfig, $config, [
            'start' => $start,
            'end'   => $end,
        ]);

        // Ensure default weights exist if not provided
        $weights = $config['weights'] ?? $this->getDefaultWeights();

        // Fetch published posts within the date range
        $posts = Post::published()
            ->whereBetween('published_at', [$config['start'], $config['end']])
            ->get();

        // Calculate engagement scores using the Metrics trait
        $scoredPosts = $this->calculateWeightedScore(
            $posts,
            $weights,
            $config['start'],
            $config['end']
        );

        // Boost score by recency if enabled
        if ($config['prioritize_recency'] ?? true) {
            $scoredPosts = $scoredPosts->map(function ($post) use ($config) {
                $hoursSincePublished = $post->published_at->diffInHours($config['end']);
                $recencyFactor = max(0.5, 1 - ($hoursSincePublished / 24));
                $post->headline_score = $post->engagement_score * $recencyFactor;
                return $post;
            });
            $sortKey = 'headline_score';
        } else {
            $sortKey = 'engagement_score';
        }

        // Filter by min_score, sort, limit
        return $scoredPosts
            ->filter(fn($post) => ($post->{$sortKey} ?? $post->engagement_score) >= ($config['min_score'] ?? 0))
            ->sortByDesc($sortKey)
            ->take($config['limit'] ?? 5)
            ->values();
    }
}
