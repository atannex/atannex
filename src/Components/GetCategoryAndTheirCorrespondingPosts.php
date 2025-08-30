<?php

namespace Atannex\Components;

use App\Models\Pages\Category;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

trait GetCategoryAndTheirCorrespondingPosts
{
    private function normalizeCategoryIds(mixed $categoryIds): array
    {
        if (is_array($categoryIds)) {
            return array_filter(array_map('intval', $categoryIds));
        }

        if (is_string($categoryIds)) {
            return array_filter(array_map('intval', explode(',', $categoryIds)));
        }

        return [];
    }

    public function getCategoryAndTheirCorrespondingPosts(array $config): Collection
    {
        $categoryIds   = $this->normalizeCategoryIds(Arr::get($config, 'category_id'));
        $limit         = max(1, (int) Arr::get($config, 'limit', 5));
        $postLimit     = max(1, (int) Arr::get($config, 'post_limit', 5));
        $leafPostLimit = max(1, (int) Arr::get($config, 'leaf_post_limit', 1));

        if (empty($categoryIds)) {
            return collect();
        }

        return Category::query()
            ->with([
                'children',
                'children.posts' => fn($query) => $query->latest('created_at')->take($leafPostLimit),
                'posts' => fn($query) => $query->latest('created_at')->take($postLimit),
            ])
            ->whereIn('id', $categoryIds)
            ->latest('created_at')
            ->take($limit)
            ->get()
            ->map(function (Category $category) use ($postLimit, $leafPostLimit): Category {
                $posts = $this->resolveCategoryPosts($category, $postLimit, $leafPostLimit);
                $category->setRelation('posts', $posts);

                return $category;
            });
    }

    private function resolveCategoryPosts(Category $category, int $postLimit, int $leafPostLimit): Collection
    {
        if ($category->children->isEmpty()) {
            return $category->posts;
        }

        return $this->getLatestPostsFromLeafCategories($category, $postLimit, $leafPostLimit);
    }

    private function getLatestPostsFromLeafCategories(Category $category, int $postLimit, int $leafPostLimit): Collection
    {
        return $category->getDescendantsAndSelf('dfs')
            ->filter(fn(Category $cat): bool => $cat->children->isEmpty())
            ->flatMap(fn(Category $leaf): Collection => $leaf->posts->take($leafPostLimit))
            ->take($postLimit);
    }
}
