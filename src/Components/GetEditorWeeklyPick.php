<?php

namespace Atannex\Components;

use Carbon\Carbon;
use App\Models\Posts\Post;
use Atannex\Traits\EngagementMetrics;
use Illuminate\Database\Eloquent\Collection;

trait GetEditorWeeklyPick
{
    use EngagementMetrics;

    /**
     * Retrieve editor's weekly picks based on engagement metrics and publication status.
     *
     * @param array $config Configuration array with optional parameters:
     *                      - start: Carbon instance for start date (default: start of week)
     *                      - end: Carbon instance for end date (default: end of week)
     *                      - limit: Number of posts to return (default: 10)
     *                      - weights: Engagement metric weights (default: from EngagementMetrics)
     *                      - min_score: Minimum engagement score threshold (default: 0)
     * @return Collection
     */
    public function getEditorWeeklyPicks(array $config = []): Collection
    {
        $defaultConfig = [
            'start' => Carbon::now()->startOfWeek(),
            'end'   => Carbon::now()->endOfWeek(),
            'limit' => 10,
            'weights' => [
                'views'    => 0.2,
                'likes'    => 0.2,
                'comments' => 0.4,
                'ratings'  => 0.1,
                'shares'   => 0.1,
            ],
            'min_score' => 0,
        ];

        $config = array_merge($defaultConfig, $config);

        $config['start'] = Carbon::parse($config['start']);
        $config['end']   = Carbon::parse($config['end']);

        $posts = Post::published()
            ->betweenDates($config['start'], $config['end'])
            ->get();

        $scoredPosts = $this->calculateWeightedScore(
            $posts,
            $config['weights'],
            $config['start'],
            $config['end']
        );

        return $scoredPosts
            ->filter(fn($post) => $post->engagement_score >= $config['min_score'])
            ->sortByDesc('engagement_score')
            ->take($config['limit'])
            ->values();
    }
}
