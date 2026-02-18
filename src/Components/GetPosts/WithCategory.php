<?php

namespace Atannex\Components\GetPosts;

use App\Models\Regions\Category;
use Illuminate\Database\Eloquent\Collection;

trait WithCategory
{
    public function getCategoryWithPosts(array $config): Collection
    {
        $categoryIds = normalizeIds($config['posts_with_id']);
        $categoryLimit = (int) $config['limit'];
        $sortBy = $config['sort'];
        $sortDir = strtolower($config['order']);
        $postLimit = (int) $config['relation_limit'];
        $leafPostLimit = (int) $config['leaf_relation_limit'];

        $categories = Category::query()
            ->whereIn('id', $categoryIds)
            ->with([
                'posts' => fn ($q) => $q->published()
                    ->orderBy($sortBy, $sortDir)
                    ->take($postLimit),
                'descendants.posts' => fn ($q) => $q->published()
                    ->orderBy($sortBy, $sortDir)
                    ->take($leafPostLimit),
            ])
            ->limit($categoryLimit)
            ->get();

        return $categories->map(function (Category $category) {
            $allPosts = $category->posts->concat(
                $category->descendants->flatMap(fn ($desc) => $desc->posts)
            );
            $category->setRelation('posts', $allPosts);

            return $category;
        });
    }
}
