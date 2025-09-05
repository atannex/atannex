<?php

namespace Atannex\Components\With;

use App\Models\Regions\Region;
use Atannex\Views\Traits\Normalize;
use Illuminate\Support\Collection;

trait GetRegionWithPosts
{
    use Normalize;

    /**
     * Get regions with their latest posts.
     *
     * @param array $config
     * @return Collection
     */
    public function getRegionWithPosts(array $config): Collection
    {
        $regionIds = array_filter(array_map('intval', $this->normalizeIds($config['region_with_post_id'])));
        $limit = max(1, (int) ($config['limit'] ?? 5));
        $postLimit = max(1, (int) ($config['post_limit'] ?? 5));
        $leafPostLimit = max(1, (int) ($config['leaf_post_limit'] ?? 1));

        if (empty($regionIds)) {
            return collect();
        }

        $regions = Region::query()
            ->select(['id', 'name', 'parent_id', 'created_at'])
            ->whereIn('id', $regionIds)
            ->with([
                'children:id,parent_id,name,created_at',
                'children.posts' => fn($q) => $q->latest('created_at')->limit($leafPostLimit),
                'posts' => fn($q) => $q->latest('created_at')->limit($postLimit),
            ])
            ->latest('created_at')
            ->limit($limit)
            ->get();

        return $regions->map(fn(Region $region) => $this->attachPosts($region, $postLimit, $leafPostLimit));
    }

    /**
     * Attach posts to a region, merging with leaf posts and removing duplicates.
     */
    private function attachPosts(Region $region, int $postLimit, int $leafPostLimit): Region
    {
        if ($region->children->isNotEmpty()) {
            $leafPosts = $this->collectLeafRegionsPosts($region, $leafPostLimit, $postLimit);
            $allPosts = $region->posts->merge($leafPosts)
                ->unique('id')
                ->sortByDesc('created_at')
                ->take($postLimit)
                ->values();

            $region->setRelation('posts', $allPosts);
        } else {

            $region->setRelation('posts', $region->posts->unique('id')->values());
        }

        return $region;
    }

    /**
     * Collect latest posts from all leaf regions of a region.
     */
    private function collectLeafRegionsPosts(Region $region, int $leafPostLimit, int $postLimit): Collection
    {
        $leafRegions = collect();
        $this->collectLeafRegions($region, $leafRegions);

        return $leafRegions
            ->flatMap(fn(Region $leaf) => $leaf->posts->take($leafPostLimit))
            ->filter()
            ->sortByDesc('created_at')
            ->take($postLimit)
            ->values();
    }

    /**
     * Recursively collect leaf regions (regions without children).
     */
    private function collectLeafRegions(Region $region, Collection &$leafRegions): void
    {
        if ($region->children->isEmpty()) {
            $leafRegions->push($region);
            return;
        }

        foreach ($region->children as $child) {
            $this->collectLeafRegions($child, $leafRegions);
        }
    }
}
