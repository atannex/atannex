<?php

namespace Atannex\Components\GetPosts;

use App\Models\Regions\Category;
use Illuminate\Support\Collection;

trait ByCategory
{
    public function getPostsForCategory(array $config = []): Collection
    {
        $categoryIds = normalizeIds($config['category_id']);
        $categoryLimit = (int) ($config['limit']);
        $sortBy = $config['sort'];
        $sortDir = strtolower($config['order']);
        $postLimit = (int) ($config['relation_limit']);
        $leafPostLimit = (int) ($config['leaf_relation_limit']);

        $categories = Category::query()
            ->whereIn('id', $categoryIds)
            ->with([
                'posts' => fn ($q) => $q->published()
                    ->orderBy($sortBy, $sortDir)
                    ->when($postLimit > 0, fn ($q) => $q->take($postLimit)),
                'descendants.posts' => fn ($q) => $q->published()
                    ->orderBy($sortBy, $sortDir)
                    ->when($leafPostLimit > 0, fn ($q) => $q->take($leafPostLimit)),
            ])
            ->when($categoryLimit > 0, fn ($q) => $q->limit($categoryLimit))
            ->get();

        return $categories->flatMap(function (Category $category) {
            return $category->posts->concat(
                $category->descendants->flatMap(fn ($desc) => $desc->posts)
            );
        })->sortBy([
            [$sortBy, $sortDir === 'asc' ? 'asc' : 'desc'],
        ])->values();
    }
}
