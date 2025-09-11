<?php

namespace Atannex\Components\GetPosts;

use App\Models\Pages\Category;
use Illuminate\Support\Collection;
use Atannex\Traits\HasPostWithHierarchy;

trait WithCategory
{
    use HasPostWithHierarchy;

    /**
     * Get categories with their latest posts.
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
     * @return Collection<int, Category>
     */
    public function getCategoryWithPosts(array $config): Collection
    {
        $categoryDefaults = [
            // Example: Override defaults if needed
            // 'children_relation' => 'category_children',
            // 'sub_relation' => 'category_posts',
            'select_fields' => ['id', 'name', 'parent_id', 'published_at'],
            'children_select_fields' => ['id', 'parent_id', 'name', 'published_at'],
            // 'sort_field' => 'category_name',
            // 'sub_sort_field' => 'updated_at',
        ];

        return $this->buildConfigAndGetHierarchy($config, Category::class, $categoryDefaults);
    }
}
