<?php

namespace Atannex\Components\GetPosts;

use App\Models\Regions\Category;
use Atannex\Traits\WithHierarchicalPosts;
use Illuminate\Support\Collection;

/**
 * Trait WithCategory
 *
 * Provides methods to retrieve categories with their hierarchical posts.
 */
trait WithCategory
{
    use WithHierarchicalPosts;

    /**
     * Retrieve categories along with their hierarchical posts.
     *
     * @param  array  $config  Configuration options:
     *                         - 'ids' => array of category IDs (required)
     *                         - 'limit' => number of top-level categories (required)
     *                         - 'relation_limit' => posts per top-level category (required)
     *                         - 'leaf_relation_limit' => posts per child category (required)
     *                         - 'sort_field' => field to sort posts by (optional)
     *                         - 'sort_direction' => sorting direction: 'asc' or 'desc' (optional)
     */
    public function getCategoryWithPosts(array $config): Collection
    {
        return $this->getHierarchicalWithPosts(
            Category::class,
            $config,
            'children',
            'posts'
        );
    }
}
