<?php

namespace Atannex\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

trait HasPostWithHierarchy
{
    use HasHierarchyWithRelations;

    /**
     * Build configuration for retrieving hierarchical models with posts.
     *
     * @param array $config
     * @param string $modelClass
     * @param array $defaultConfig
     * @return Collection
     */
    protected function buildConfigAndGetHierarchy(array $config, string $modelClass, array $defaultConfig = []): Collection
    {
        $baseConfig = [
            'model_class' => $modelClass,
            'ids' => Arr::get($config, 'posts_with_id'),
            'limit' => max(1, (int) Arr::get($config, 'limit', 5)),
            'sub_limit' => max(1, (int) Arr::get($config, 'post_limit', 5)),
            'leaf_sub_limit' => max(1, (int) Arr::get($config, 'leaf_post_limit', 1)),
            'children_relation' => 'children',
            'sub_relation' => 'posts',
            'select_fields' => [
                'id',
                'name',
                'parent_id',
                'created_at'
            ],
            'children_select_fields' => [
                'id',
                'parent_id',
                'name',
                'created_at'
            ],
            'sort_field' => 'created_at',
            'sub_sort_field' => 'created_at',
        ];

        $mergedConfig = array_merge($baseConfig, $defaultConfig, $config);

        return $this->getHierarchyWithRelations($mergedConfig);
    }
}
