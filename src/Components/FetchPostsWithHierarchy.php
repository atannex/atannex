<?php

declare(strict_types=1);

namespace Atannex\Components;

use App\Models\Regions\Category;
use App\Models\Regions\Region;
use Illuminate\Support\Collection;

trait FetchPostsWithHierarchy
{
    /*
    |--------------------------------------------------------------------------
    | Category Posts
    |--------------------------------------------------------------------------
    */

    public function getCategoryWithPosts(array $config = []): Collection
    {
        return $this->getHierarchyPosts(Category::class, $config);
    }

    /*
    |--------------------------------------------------------------------------
    | Region Posts
    |--------------------------------------------------------------------------
    */

    public function getRegionWithPosts(array $config = []): Collection
    {
        return $this->getHierarchyPosts(Region::class, $config);
    }

    /*
    |--------------------------------------------------------------------------
    | Core Hierarchy Logic
    |--------------------------------------------------------------------------
    */

    private function getHierarchyPosts(string $model, array $config = []): Collection
    {
        $ids = normalizeIds($config['posts_with_id']);
        $limit = (int) ($config['limit']);
        $sortBy = $config['sort'];
        $sortDir = strtolower($config['order']);
        $postLimit = (int) ($config['relation_limit']);
        $leafPostLimit = (int) ($config['leaf_relation_limit']);

        $items = $model::query()
            ->whereIn('id', $ids)
            ->with([
                'posts' => fn($q) => $this->applyPostConstraints($q, $sortBy, $sortDir, $postLimit),
                'descendants.posts' => fn($q) => $this->applyPostConstraints($q, $sortBy, $sortDir, $leafPostLimit),
            ])
            ->when($limit > 0, fn($q) => $q->limit($limit))
            ->get();

        return $items->map(function ($item) {
            $allPosts = $item->posts->concat(
                $item->descendants->flatMap(fn($desc) => $desc->posts)
            );

            $item->setRelation('posts', $allPosts);

            return $item;
        });
    }
}
