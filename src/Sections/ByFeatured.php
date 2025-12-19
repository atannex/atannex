<?php

namespace Atannex\Sections;

use App\Enums\Flag;
use App\Models\Posts\Post;
use Illuminate\Support\Collection;

trait ByFeatured
{
    /**
     * Get featured posts with optional limit.
     */
    public function getFeaturedPosts(int $limit = 5): Collection
    {
        return Post::query()
            ->where('flag', Flag::FEATURED)
            ->flagged(Flag::PUBLISHED)
            ->published()
            ->where(function ($query) {
                $query->whereNull('featured_until')
                    ->orWhere('featured_until', '>=', now());
            })
            ->orderByDesc('feature_priority')
            ->orderByDesc('published_at')
            ->take($limit)
            ->get();
    }
}
