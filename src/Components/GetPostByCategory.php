<?php

namespace Atannex\Components;

use App\Models\Posts\Post;
use App\Models\Pages\Category;
use Illuminate\Support\Collection;

trait GetPostByCategory
{
    /**
     * Retrieve posts by category IDs, fetching all posts for leaf categories
     * or the first post per leaf category for parent categories.
     *
     * @param array $config Configuration array containing 'category_id' (single ID or array of IDs)
     *                      and optional 'limit' for the number of posts to return.
     * @return Collection A collection of Post models.
     */
    public function getPostByCategory(array $config): Collection
    {
        // Ensure category_id is an array for consistent processing
        $categoryIds = (array) $config['category_id'];

        // Fetch categories with their children
        $categories = Category::with('children')
            ->whereIn('id', $categoryIds)
            ->get();

        // Initialize collection to store posts
        $allPosts = collect();

        foreach ($categories as $category) {
            // Check if the category is a leaf (no children)
            $isLeaf = !$category->children()->exists();

            if ($isLeaf) {
                // Fetch all published posts for a leaf category
                $posts = Post::query()
                    ->published()
                    ->where('category_id', $category->id)
                    ->latest()
                    ->get();
                $allPosts = $allPosts->merge($posts);
            } else {
                // For non-leaf categories, get descendants and filter for leaf categories
                $descendants = $category->getDescendantsAndSelf('dfs');
                $leafCategories = $descendants->filter(fn($cat) => !$cat->children()->exists());

                foreach ($leafCategories as $leaf) {
                    // Fetch the latest published post for each leaf descendant
                    $post = Post::query()
                        ->published()
                        ->where('category_id', $leaf->id)
                        ->latest()
                        ->first();
                    if ($post) {
                        $allPosts->push($post);
                    }
                }
            }
        }

        // Apply limit if specified in config
        if (!empty($config['limit'])) {
            $allPosts = $allPosts->take($config['limit']);
        }

        // Return the collection with reset keys
        return $allPosts->values();
    }
}
