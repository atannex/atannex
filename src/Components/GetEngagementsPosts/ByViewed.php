<?php

namespace Atannex\Components\GetEngagementsPosts;

use App\Models\Posts\Post;
use Illuminate\Support\Collection;

trait ByViewed
{
    public function getMostViewedPosts(array $config = []): Collection
    {
        $limit = $config['limit'];
        $categoryId = $config['category_id'];
        $tagId = $config['tag_id'];
        $relations = $config['with'];

        $query = Post::query()
            ->published()
            ->when($categoryId, fn ($q) => $q->where('category_id', $categoryId))
            ->when($tagId, fn ($q) => $q->whereHas('tags', fn ($q) => $q->where('id', $tagId)))
            ->with($relations)
            ->orderByDesc('views')
            ->limit($limit);

        return $query->get();
    }
}
