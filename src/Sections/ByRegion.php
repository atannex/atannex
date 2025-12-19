<?php

namespace Atannex\Sections;

use App\Enums\Flag;
use App\Models\Regions\Region;
use Illuminate\Support\Collection;

trait ByRegion
{
    /**
     * Retrieve top-level regions along with their posts
     * and posts from all leaf descendants.
     *
     * @param  int  $limit
     * @return Collection<int, Region>
     */
    public function getRegionWithPost(int $limit = 5): Collection
    {
        $regions = Region::with([
            'posts' => fn($query) =>
            $query->flagged(Flag::PUBLISHED),
            'children',
        ])
            ->whereNull('parent_id')
            ->get();

        return $regions->map(function (Region $region) use ($limit) {

            $descendants = $region->getDescendants();

            $leafRegions = $descendants
                ->filter(fn(Region $descendant) => $descendant->children->isEmpty());

            $allPosts = $region->posts->merge(
                $leafRegions->flatMap(
                    fn(Region $leaf) =>
                    $leaf->posts->where('flag', Flag::PUBLISHED)
                )
            );

            $region->allPosts = $allPosts->take($limit)->values();

            return $region;
        });
    }
}
