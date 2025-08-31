<?php

namespace Atannex\Components;

use App\Models\Posts\Post;
use App\Models\Pages\Category;
use Illuminate\Support\Collection;

trait GetPostsForCategory
{
    public function getPostsForCategory(array $config): Collection
    {
        $categoryIds = (array) ($config['category_id'] ?? []);
        if (empty($categoryIds)) {
            return collect();
        }

        $categories = Category::with('children')->whereIn('id', $categoryIds)->get();
        $allPosts = collect();

        foreach ($categories as $category) {
            if ($category->children->isEmpty()) {
                $posts = Post::published()
                    ->where('category_id', $category->id)
                    ->latest()
                    ->take($config['limit'] ?? 15)
                    ->get();
                $allPosts = $allPosts->merge($posts);
            } else {
                $descendants = $category->getDescendantsAndSelf('dfs');
                $leafIds = $descendants->filter(fn($cat) => $cat->children->isEmpty())->pluck('id');

                if ($leafIds->isNotEmpty()) {
                    $posts = Post::published()
                        ->whereIn('category_id', $leafIds)
                        ->latest()
                        ->get()
                        ->groupBy('category_id')
                        ->flatMap(fn($group) => $group->take($config['limit_per_leaf_post'] ?? 1));
                    $allPosts = $allPosts->merge($posts);
                }
            }
        }

        return $allPosts->unique('id')->sortByDesc('created_at')->values();
    }
}
