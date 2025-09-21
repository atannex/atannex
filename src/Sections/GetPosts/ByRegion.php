<?php

namespace Atannex\Sections\GetPosts;

use App\Models\Regions\Region;
use Illuminate\Support\Collection;

trait ByRegion
{
    /**
     * Retrieve top-level regions along with their posts and posts from all leaf descendants.
     *
     * @param int $limit Number of posts to include per region (default: 5)
     * @return Collection<int, Region>
     */
    public function getRegionWithPost(int $limit = 5): Collection
    {
        $regions = Region::with(['posts', 'children'])
            ->whereNull('parent_id')
            ->get();

        return $regions->map(function (Region $region) use ($limit) {

            $descendants = $region->getDescendants();

            $leafRegions = $descendants->filter(fn(Region $descendant) => $descendant->children->isEmpty());

            $allPosts = $region->posts->merge(
                $leafRegions->flatMap(fn(Region $leaf) => $leaf->posts)
            );

            $region->allPosts = $allPosts->take($limit);

            return $region;
        });
    }
}
