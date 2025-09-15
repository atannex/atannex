<?php

namespace Atannex\Components\GetPosts;

use Illuminate\Support\Arr;
use App\Models\Pages\Category;
use Atannex\Views\Traits\CanNormalize;
use Illuminate\Support\Collection;

trait WithCategory
{
    use CanNormalize;

    /**
     * Get categories with posts.
     *
     * @param array $config
     * [
     *   'ids' => [1,2,3],              // category IDs
     *   'limit' => 4,                   // max number of categories
     *   'relation_limit' => 3,          // posts per category
     *   'leaf_relation_limit' => 1,     // posts per leaf category
     *   'order_column' => 'published_at'
     * ]
     *
     * @return Collection
     */
     /**
     * Get categories with their latest posts.
     *
     * @param array $config
     * @return Collection
     */
    public function getCategoryWithPosts(array $config): Collection
    {
        $categoryIds = $this->normalizeIds(Arr::get($config, 'posts_with_id'));
        $limit = max(1, (int) Arr::get($config, 'limit'));
        $postLimit = max(1, (int) Arr::get($config, 'relation_limit', 3));
        $leafPostLimit = max(1, (int) Arr::get($config, 'leaf_relation_limit', 1));

        if (empty($categoryIds)) {
            return collect();
        }

        $categoryIds = array_map('intval', array_filter($categoryIds));

        if ($categoryIds === []) {
            return collect();
        }

        $categories = Category::query()
            ->select(['id', 'name', 'parent_id', 'published_at'])
            ->whereIn('id', $categoryIds)
            ->with([
                'children:id,parent_id,name,published_at',
                'children.posts' => function ($query) use ($leafPostLimit) {
                    $query->latest('published_at')->limit($leafPostLimit);
                },
                'posts' => function ($query) use ($postLimit) {
                    $query->latest('published_at')->limit($postLimit);
                }
            ])
            ->latest('published_at')
            ->limit($limit)
            ->get();

        return $categories->map(function (Category $category) use ($postLimit, $leafPostLimit) {
            $posts = $this->resolveCategoryPosts($category, $postLimit, $leafPostLimit);
            $category->setRelation('posts', $posts);
            return $category;
        });
    }


    /**
     * Resolve posts for a category, including leaf categories.
     *
     * @param Category $category
     * @param int $postLimit
     * @param int $leafPostLimit
     * @return Collection
     */
    private function resolveCategoryPosts(Category $category, int $postLimit, int $leafPostLimit): Collection
    {
        if ($category->children->isEmpty()) {
            return $category->posts;
        }

        return $this->getLatestPostsFromLeafCategories($category, $postLimit, $leafPostLimit);
    }

    /**
     * Collect latest posts from all leaf categories of a category.
     * Optimized to avoid N+1 queries and unnecessary traversals.
     *
     * @param Category $category
     * @param int $postLimit
     * @param int $leafPostLimit
     * @return Collection
     */
    private function getLatestPostsFromLeafCategories(Category $category, int $postLimit, int $leafPostLimit): Collection
    {
        $leafCategories = collect();
        $this->collectLeafCategories($category, $leafCategories);

        return $leafCategories
            ->flatMap(function (Category $leaf) use ($leafPostLimit) {
                return $leaf->posts->take($leafPostLimit);
            })
            ->sortByDesc('created_at')
            ->take($postLimit)
            ->values();
    }

    /**
     * Recursively collect leaf categories (categories without children).
     * More efficient than getDescendantsAndSelf for this specific use case.
     *
     * @param Category $category
     * @param Collection $leafCategories
     * @return void
     */
    private function collectLeafCategories(Category $category, Collection &$leafCategories): void
    {
        if ($category->children->isEmpty()) {
            $leafCategories->push($category);
            return;
        }

        foreach ($category->children as $child) {
            $this->collectLeafCategories($child, $leafCategories);
        }
    }
}
