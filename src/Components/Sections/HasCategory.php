<?php

declare(strict_types=1);

namespace Atannex\Components\Sections;

use App\Models\Posts\Post;
use App\Models\Regions\Category;
use Illuminate\Support\Collection;

trait HasCategory
{
    /**
     * Retrieve categories matching the given name and attach their most recent published posts.
     *
     * @param string $name The category name to match.
     * @param int $limit The maximum number of posts to attach to each category.
     * @return Collection Collection of Category models, each with an `allPosts` property containing up to `$limit` published posts ordered by `published_at` (newest first).
     */
    public function categoriesWithPostsByName(string $name, int $limit = 5): Collection
    {
        return Category::where('name', $name)
            ->get()
            ->map(function (Category $category) use ($limit) {
                $categoryIds = $category->getDescendants()
                    ->pluck('id')
                    ->push($category->id);

                $category->allPosts = Post::whereIn('category_id', $categoryIds)
                    ->published()
                    ->latest('published_at')
                    ->limit($limit)
                    ->get();

                return $category;
            });
    }
}