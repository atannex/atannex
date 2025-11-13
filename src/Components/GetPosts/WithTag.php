<?php

namespace Atannex\Components\GetPosts;

use App\Models\Tags\Tag;
use Atannex\Traits\WithHierarchicalPosts;
use Illuminate\Support\Collection;

/**
 * Trait WithTag
 *
 * Provides methods to retrieve tags with their associated hierarchical posts.
 */
trait WithTag
{
    use WithHierarchicalPosts;

    /**
     * Retrieve tags along with their hierarchical posts.
     *
     * @param  array  $config  Configuration options:
     *                         - 'ids' => array of tag IDs (optional)
     *                         - 'limit' => number of top-level tags (optional)
     *                         - 'relation_limit' => posts per top-level tag (optional)
     *                         - 'leaf_relation_limit' => posts per child tag (optional)
     *                         - 'sort_field' => field to sort posts by (optional)
     *                         - 'sort_direction' => sorting direction: 'asc' or 'desc' (optional)
     */
    public function getTagWithPosts(array $config): Collection
    {
        return $this->getHierarchicalWithPosts(
            Tag::class,
            $config,
            'children',
            'posts'
        );
    }
}
