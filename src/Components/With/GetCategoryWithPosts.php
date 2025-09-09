<?php

namespace Atannex\Components\With;

use Illuminate\Support\Arr;
use App\Models\Pages\Category;
use Illuminate\Support\Collection;
use Atannex\Traits\HasHierarchyWithRelations;

trait GetCategoryWithPosts
{
    use HasHierarchyWithRelations;

    /**
     * Get categories with their latest posts.
     *
     * @param array $config
     * @return \Illuminate\Support\Collection
     */
    public function getCategoryWithPosts(array $config): Collection
    {
        $mappedConfig = [
            'model_class' => Category::class,
            'ids' => Arr::get($config, 'posts_with_id'),
            'limit' => Arr::get($config, 'limit', 5),
            'sub_limit' => Arr::get($config, 'post_limit', 5),
            'leaf_sub_limit' => Arr::get($config, 'leaf_posts_limit', 1),
            'children_relation' => 'children',
            'sub_relation' => 'posts',
        ];

        return $this->getHierarchyWithRelations($mappedConfig);
    }
}
