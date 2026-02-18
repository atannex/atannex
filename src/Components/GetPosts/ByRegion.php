<?php

namespace Atannex\Components\GetPosts;

use App\Models\Regions\Region;
use Illuminate\Support\Collection;

trait ByRegion
{
    public function getPostsForRegion(array $config = []): Collection
    {
        $regionIds = normalizeIds($config['region_id']);
        $regionLimit = (int) ($config['limit']);
        $sortBy = $config['sort'];
        $sortDir = strtolower($config['order']);
        $postLimit = (int) ($config['relation_limit']);
        $leafPostLimit = (int) ($config['leaf_relation_limit']);

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
            ->when($regionLimit > 0, fn ($q) => $q->limit($regionLimit))
            ->get();

        return $regions->flatMap(function (Region $region) {
            return $region->posts->concat(
                $region->descendants->flatMap(fn ($desc) => $desc->posts)
            );
        })->sortBy([
            [$sortBy, $sortDir === 'asc' ? 'asc' : 'desc'],
        ])->values();
    }
}
