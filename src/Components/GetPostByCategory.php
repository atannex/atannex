<?php

namespace Atannex\Components;

use App\Models\Posts\Post;
use App\Models\Pages\Category;
use Illuminate\Support\Collection;

trait GetPostByCategory
{
    /**
     * Retrieve posts by category IDs based on parent/leaf logic.
     *
     * Rules:
     * - Parent categories inherit posts from their descendant leaf categories
     *   (return 2 latest posts per leaf).
     * - Directly chosen leaf categories return up to 15 latest posts each.
     *
     * @param array $config Configuration array containing 'category_id' (single ID or array of IDs).
     * @return Collection A collection of Post models.
     */
    public function getPostByCategory(array $config): Collection
    {
        $categoryIds = (array) $config['category_id'];
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

                foreach ($leafIds as $leafId) {
                    $posts = Post::published()
                        ->where('category_id', $leafId)
                        ->latest()
                        ->take($config['limit_per_leaf_post'] ?? 1)
                        ->get();

                    $allPosts = $allPosts->merge($posts);
                }
            }
        }
        return $allPosts->sortByDesc('created_at')->values();
    }
}
