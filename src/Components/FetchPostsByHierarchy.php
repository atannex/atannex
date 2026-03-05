<?php

declare(strict_types=1);

namespace Atannex\Components;

use App\Models\Regions\Category;
use App\Models\Regions\Region;
use Illuminate\Support\Collection;

trait FetchPostsByHierarchy
{
    /*
    |--------------------------------------------------------------------------
    | Region Posts
    |--------------------------------------------------------------------------
    */

    public function getPostsForRegion(array $config = []): Collection
    {
        return $this->getPostsFromHierarchy(Region::class, 'region_id', $config);
    }

    /*
    |--------------------------------------------------------------------------
    | Category Posts
    |--------------------------------------------------------------------------
    */

    public function getPostsForCategory(array $config = []): Collection
    {
        return $this->getPostsFromHierarchy(Category::class, 'category_id', $config);
    }

    /*
    |--------------------------------------------------------------------------
    | Core Hierarchy Logic
    |--------------------------------------------------------------------------
    */

    private function getPostsFromHierarchy(string $model, string $configKey, array $config = []): Collection
    {
        $ids = normalizeIds($config[$configKey]);
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

        return $items
            ->flatMap(
                fn($item) =>
                $item->posts->concat(
                    $item->descendants->flatMap(fn($desc) => $desc->posts)
                )
            )
            ->sortBy([
                [$sortBy, $sortDir === 'asc' ? 'asc' : 'desc'],
            ])
            ->values();
    }
}
