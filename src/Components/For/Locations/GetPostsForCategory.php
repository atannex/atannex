<?php

namespace Atannex\Components\For\Locations;

use App\Models\Posts\Post;
use App\Models\Pages\Category;
use Illuminate\Support\Collection;

trait GetPostsForCategory
{

    public function getPostsForCategory(array $config = []): Collection
    {
        $categoryIds      = (array) ($config['category_id']);
        $limit            = max(1, (int) ($config['limit'] ?? 5));
        $limitPerLeafPost = max(1, (int) ($config['limit_per_leaf_post'] ?? 1));

        $sortBy  = $config['sort_by'] ?? 'published_at';
        $sortDir = $config['sort_dir'] ?? 'desc';

        if ($categoryIds === []) {
            return collect();
        }

        $categories = Category::with('children')->whereIn('id', $categoryIds)->get();
        $allPosts = collect();

        foreach ($categories as $category) {
            if ($category->children->isEmpty()) {
                $posts = Post::published()
                    ->where('category_id', $category->id)
                    ->orderBy($sortBy, $sortDir)
                    ->take($limit)
                    ->get();

                $allPosts = $allPosts->merge($posts);
            } else {
                $leafIds = $category->getDescendants()
                    ->filter(fn($c) => $c->children->isEmpty())
                    ->pluck('id')
                    ->all();

                if (empty($leafIds)) {
                    continue;
                }

                $posts = Post::published()
                    ->whereIn('category_id', $leafIds)
                    ->orderBy($sortBy, $sortDir)
                    ->get()
                    ->groupBy('category_id')
                    ->flatMap(fn($group) => $group->take($limitPerLeafPost));

                $allPosts = $allPosts->merge($posts);
            }
        }

        return $allPosts
            ->unique('id')
            ->sortBy([$sortBy => $sortDir === 'desc' ? SORT_DESC : SORT_ASC])
            ->values();
    }
}
