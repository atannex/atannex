<?php

declare(strict_types=1);

namespace Atannex\Components\Sections;

use App\Models\Posts\Post;
use App\Models\Regions\Category;
use Illuminate\Support\Collection;

trait HasCategory
{
    /**
     * Get categories by name along with their latest published posts.
     *
     * @param string $name
     * @param int $limit
     * @return Collection
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
