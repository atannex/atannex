<?php

namespace Atannex\Components;

use Carbon\Carbon;
use App\Models\Posts\Post;
use Atannex\Traits\Metrics;
use Illuminate\Database\Eloquent\Collection;

trait GetEditorWeeklyPick
{
    use Metrics;

    /**
     * Retrieve editor's weekly picks based on engagement metrics and publication status.
     *
     * @param array $config Optional configuration:
     *                      - start: Carbon instance or date string (default: start of week)
     *                      - end: Carbon instance or date string (default: end of week)
     *                      - limit: Number of posts to return
     *                      - weights: Engagement metric weights
     *                      - min_score: Minimum engagement score threshold
     * @return Collection
     */
    public function getEditorWeeklyPicks(array $config = []): Collection
    {
        // Load default config from file
        $defaultConfig = config('editor_picks');

        // Determine dynamic start and end dates
        $start = $config['start'] ?? Carbon::now()->startOfWeek();
        $end   = $config['end'] ?? Carbon::now()->endOfWeek();

        $start = $start instanceof Carbon ? $start : Carbon::parse($start);
        $end   = $end instanceof Carbon ? $end : Carbon::parse($end);

        // Merge defaults with user config, giving priority to user values
        $config = array_merge($defaultConfig, $config, [
            'start' => $start,
            'end'   => $end,
        ]);

        // Fetch published posts in date range
        $posts = Post::published(false)
            ->betweenDates($config['start'], $config['end'])
            ->get();

        // Calculate engagement scores using weights from config
        $scoredPosts = $this->calculateWeightedScore(
            $posts,
            $config['weights'],
            $config['start'],
            $config['end']
        );

        // Filter by min_score, sort descending, and limit results
        return $scoredPosts
            ->filter(fn($post) => $post->engagement_score >= $config['min_score'])
            ->sortByDesc('engagement_score')
            ->take($config['limit'])
            ->values();
    }
}
