<?php

namespace Atannex\Components\With;

use Illuminate\Support\Arr;
use App\Models\Regions\Region;
use Illuminate\Support\Collection;
use Atannex\Traits\HasHierarchyWithRelations;

trait GetRegionWithPosts
{
    use HasHierarchyWithRelations;

    /**
     * Get regions with their latest posts using HasHierarchyWithRelations trait.
     *
     * @param array $config
     * @return Collection
     */
    public function getRegionWithPosts(array $config): Collection
    {
        $config = [
            'model_class' => Region::class,
            'ids' => Arr::get($config, 'posts_with_id'),
            'limit' => max(1, (int) ($config['limit'] ?? 5)),
            'sub_limit' => max(1, (int) ($config['post_limit'] ?? 5)),
            'leaf_sub_limit' => max(1, (int) ($config['leaf_post_limit'] ?? 1)),
            'children_relation' => 'children',
            'sub_relation' => 'posts',
            'select_fields' => ['id', 'name', 'parent_id', 'created_at'],
            'children_select_fields' => ['id', 'parent_id', 'name', 'created_at'],
            'sort_field' => 'created_at',
            'sub_sort_field' => 'created_at',
        ];

        return $this->getHierarchyWithRelations($config);
    }
}
