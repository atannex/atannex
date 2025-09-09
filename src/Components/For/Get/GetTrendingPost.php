<?php

namespace Atannex\Components\For\Get;

use Carbon\Carbon;
use App\Models\Posts\Post;
use Atannex\Traits\HasMetrics;
use Illuminate\Database\Eloquent\Collection;

trait GetTrendingPost
{
    use HasMetrics;

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
