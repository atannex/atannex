<?php

declare(strict_types=1);

namespace Atannex\Components\Sections;

use App\Models\Regions\Region;
use Illuminate\Support\Collection;

trait HasRegion
{
    /**
     * Retrieve top-level regions and attach their recent published posts including posts from all leaf descendant regions.
     *
     * For each returned Region, an `allPosts` Collection is added containing up to `$limit` published posts aggregated from the region itself and all of its leaf descendants, sorted by `published_at` in descending order.
     *
     * @param int $limit Maximum number of posts to include per region.
     * @return Collection<int, Region> A collection of top-level Region instances, each augmented with an `allPosts` collection of up to `$limit` most recent published posts.
     */
    public function hasRegionWithPost(int $limit = 5): Collection
    {
        $regions = Region::with([
            'posts' => fn($query) => $query->published(),
            'children.posts' => fn($query) => $query->published(),
            'children.children.posts' => fn($query) => $query->published(),
        ])
            ->whereNull('parent_id')
            ->get();

        return $regions->map(function (Region $region) use ($limit) {

            $leafRegions = $region->getDescendants()
                ->filter(fn(Region $descendant) => $descendant->children->isEmpty());

            $allPosts = $region->posts->merge(
                $leafRegions->flatMap(fn(Region $leaf) => $leaf->posts)
            )->sortByDesc('published_at');

            $region->allPosts = $allPosts->take($limit)->values();

            return $region;
        });
    }
}