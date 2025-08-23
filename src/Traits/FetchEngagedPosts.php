<?php

namespace Atannex\Traits;

use Carbon\Carbon;
use App\Models\Posts\Post;
use Atannex\Traits\Metrics;
use Illuminate\Database\Eloquent\Collection;

trait FetchEngagedPosts
{
    use Metrics;

    /**
     * Generic method to fetch posts with engagement scoring and filtering.
     *
     * @param array $config Configuration options:
     *                      - start: Carbon/date string
     *                      - end: Carbon/date string
     *                      - limit: int
     *                      - weights: array
     *                      - min_score: float
     *                      - recency_boost: bool
     * @return Collection
     */
    public function fetchEngagedPosts(array $config = []): Collection
    {
        // Load default config
        $defaultConfig = config('editor_picks');

        // Normalize dates
        $start = $config['start'] ?? Carbon::today();
        $end   = $config['end'] ?? Carbon::today()->endOfDay();
        $start = $start instanceof Carbon ? $start : Carbon::parse($start);
        $end   = $end instanceof Carbon ? $end : Carbon::parse($end);

        // Merge configs
        $config = array_merge($defaultConfig, $config, [
            'start' => $start,
            'end'   => $end,
        ]);

        $weights = $config['weights'] ?? $this->getDefaultWeights();
        $minScore = $config['min_score'] ?? 0;
        $limit = $config['limit'] ?? 5;
        $recencyBoost = $config['recency_boost'] ?? false;

        // Fetch published posts
        $posts = Post::published()
            ->whereBetween('published_at', [$start, $end])
            ->get();

        // Calculate engagement scores
        $scoredPosts = $this->calculateWeightedScore($posts, $weights, $start, $end);

        // Optional recency boost
        if ($recencyBoost) {
            $scoredPosts = $scoredPosts->map(function ($post) use ($end) {
                $hoursSincePublished = $post->published_at->diffInHours($end);
                $recencyFactor = max(0.5, 1 - ($hoursSincePublished / 24));
                $post->engagement_score = $post->engagement_score * $recencyFactor;
                return $post;
            });
        }

        // Filter, sort, and limit
        return $scoredPosts
            ->filter(fn($post) => $post->engagement_score >= $minScore)
            ->sortByDesc('engagement_score')
            ->take($limit)
            ->values();
    }
}
