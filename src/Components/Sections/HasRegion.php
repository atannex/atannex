<?php

declare(strict_types=1);

namespace Atannex\Components\Sections;

use App\Models\Regions\Region;
use Illuminate\Support\Collection;

trait HasRegion
{
    /**
     * Retrieve top-level regions with their published posts
     * and posts from all leaf descendant regions.
     *
     * @param int $limit Maximum posts per region
     * @return Collection<int, Region>
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
