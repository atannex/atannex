<?php

namespace Atannex\Components\With;

use App\Models\Tags\Tag;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Atannex\Traits\HasHierarchyWithRelations;

trait GetTagWithPosts
{
    use HasHierarchyWithRelations;

    /**
     * Retrieve tags with their latest posts using HasHierarchyWithRelations trait.
     *
     * @param array{
     *     tag_with_post_id: array<int>,
     *     limit?: int,
     *     post_limit?: int
     * } $config
     *
     * @return Collection<int, Tag>
     */
    public function getTagWithPosts(array $config): Collection
    {
        $config = [
            'model_class' => Tag::class,
            'ids' => Arr::get($config, 'posts_with_id'),
            'limit' => max(1, (int) ($config['limit'] ?? 5)),
            'sub_limit' => max(1, (int) ($config['post_limit'] ?? 5)),
            'leaf_sub_limit' => max(1, (int) ($config['post_limit'] ?? 5)),
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
