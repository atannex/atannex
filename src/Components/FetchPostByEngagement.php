<?php

declare(strict_types=1);

namespace Atannex\Components;

use App\Models\Posts\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

trait FetchPostByEngagement
{
    /*
    |--------------------------------------------------------------------------
    | Public Engagement Methods
    |--------------------------------------------------------------------------
    */

    public function getMostCommentedPosts(array $config = []): Collection
    {
        return $this->engagementQuery($config)
            ->orderByDesc('comments_count')
            ->get();
    }

    public function getMostLikedPosts(array $config = []): Collection
    {
        return $this->engagementQuery($config)
            ->orderByDesc('likes')
            ->get();
    }

    public function getTopRatedPosts(array $config = []): Collection
    {
        $orderBy = $config['orderBy'];

        return $this->engagementQuery($config)
            ->orderByDesc($orderBy)
            ->get();
    }

    public function getMostSharedPosts(array $config = []): Collection
    {
        return $this->engagementQuery($config)
            ->orderByDesc('shares')
            ->get();
    }

    public function getMostViewedPosts(array $config = []): Collection
    {
        return $this->engagementQuery($config)
            ->orderByDesc('views')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Base Engagement Query
    |--------------------------------------------------------------------------
    */

    private function engagementQuery(array $config): Builder
    {
        $limit = $config['limit'];
        $categoryId = $config['category_id'];
        $tagId = $config['tag_id'];
        $relations = $config['with'];

        return Post::query()
            ->published()
            ->when($categoryId, fn($q) => $q->where('category_id', $categoryId))
            ->when($tagId, fn($q) => $q->whereHas('tags', fn($q) => $q->where('id', $tagId)))
            ->with($relations)
            ->limit($limit);
    }
}
