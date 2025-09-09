<?php

namespace Atannex\Components\With;

use App\Models\Tags\Tag;
use Illuminate\Support\Collection;

trait GetTagWithPosts
{
    use BaseHierarchyWithPosts;

    /**
     * Retrieve tags with their latest posts.
     *
     * @param array{
     *     tag_with_post_id?: array<int>,
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
     * @return Collection<int, Tag>
     */
    public function getTagWithPosts(array $config): Collection
    {
        $tagDefaults = [
            // Example: Override defaults if needed
            // 'children_relation' => 'tag_children',
            // 'sub_relation' => 'tag_posts',
            // 'select_fields' => ['id', 'title', 'parent_id', 'created_at'],
            // 'children_select_fields' => ['id', 'parent_id', 'title', 'created_at'],
            // 'sort_field' => 'title',
            // 'sub_sort_field' => 'published_at',
        ];

        return $this->buildConfigAndGetHierarchy($config, Tag::class, $tagDefaults);
    }
}
