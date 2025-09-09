<?php

namespace Atannex\Components\With;

use App\Models\Regions\Region;
use Illuminate\Support\Collection;

trait GetRegionWithPosts
{
    use BaseHierarchyWithPosts;

    /**
     * Get regions with their latest posts.
     *
     * @param array{
     *     posts_with_id?: array<int>,
     *     limit?: int,
     *     post_limit?: int,
     *     leaf_post_limit?: int,
     *     children_relation?: string,
     *     sub_relation?: string,
     *     select_fields?: array<string>,
     *     children_select_fields?: array<string>,
     *     sort_field?: string,
     *     sub_sort_field?: string
     * } $config
     *
     * @return Collection<int, Region>
     */
    public function getRegionWithPosts(array $config): Collection
    {
        $regionDefaults = [
            // Example: Override defaults if needed
            // 'children_relation' => 'region_children',
            // 'sub_relation' => 'region_posts',
            // 'select_fields' => ['id', 'region_name', 'parent_id', 'created_at'],
            // 'children_select_fields' => ['id', 'parent_id', 'region_name', 'created_at'],
            // 'sort_field' => 'region_name',
            // 'sub_sort_field' => 'created_at',
        ];

        return $this->buildConfigAndGetHierarchy($config, Region::class, $regionDefaults);
    }
}
