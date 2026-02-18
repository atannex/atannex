<?php

namespace Atannex\Components\GetPosts;

use App\Models\Regions\Region;
use Illuminate\Support\Collection;

trait WithRegion
{
    public function getRegionWithPosts(array $config): Collection
    {
        $regionIds = normalizeIds($config['posts_with_id']);
        $categoryLimit = (int) $config['limit'];
        $sortBy = $config['sort'];
        $sortDir = strtolower($config['order']);
        $postLimit = (int) $config['relation_limit'];
        $leafPostLimit = (int) $config['leaf_relation_limit'];

        $regions = Region::query()
            ->whereIn('id', $regionIds)
            ->with([
                'posts' => fn ($q) => $q->published()
                    ->orderBy($sortBy, $sortDir)
                    ->when($postLimit > 0, fn ($q) => $q->take($postLimit)),
                'descendants.posts' => fn ($q) => $q->published()
                    ->orderBy($sortBy, $sortDir)
                    ->when($leafPostLimit > 0, fn ($q) => $q->take($leafPostLimit)),
            ])
            ->when($categoryLimit > 0, fn ($q) => $q->limit($categoryLimit))
            ->get();

        return $regions->map(function (Region $region) {
            $allPosts = $region->posts->concat(
                $region->descendants->flatMap(fn ($desc) => $desc->posts)
            );
            $region->setRelation('posts', $allPosts);

            return $region;
        });
    }
}
