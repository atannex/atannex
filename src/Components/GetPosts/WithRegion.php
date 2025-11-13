<?php

namespace Atannex\Components\GetPosts;

use App\Models\Regions\Region;
use Atannex\Traits\WithHierarchicalPosts;
use Illuminate\Support\Collection;

/**
 * Trait WithRegion
 *
 * Provides methods to retrieve regions with their hierarchical posts.
 */
trait WithRegion
{
    use WithHierarchicalPosts;

    /**
     * Retrieve regions along with their hierarchical posts.
     *
     * @param  array  $config  Configuration options:
     *                         - 'ids' => array of region IDs (required)
     *                         - 'limit' => number of top-level regions (required)
     *                         - 'relation_limit' => posts per top-level region (required)
     *                         - 'leaf_relation_limit' => posts per child region (required)
     *                         - 'sort_field' => field to sort posts by (optional)
     *                         - 'sort_direction' => sorting direction: 'asc' or 'desc' (optional)
     */
    public function getRegionWithPosts(array $config): Collection
    {
        return $this->getHierarchicalWithPosts(
            Region::class,
            $config,
            'children',
            'posts'
        );
    }
}
