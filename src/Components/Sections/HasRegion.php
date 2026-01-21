<?php

declare(strict_types=1);

namespace Atannex\Components\Sections;

use App\Models\Regions\Region;
use Illuminate\Support\Collection;

trait HasRegion
{
    public function hasRegionWithPost(int $limit = 5): Collection
    {
        $regions = Region::with('children')->whereNull('parent_id')->get();
        return $regions->map(function (Region $region) use ($limit) {
            $regionIds = $region->getSelfAndDescendantIds();

            $region->allPosts = Region::whereIn('id', $regionIds)
                ->with(['posts' => fn($query) => $query->published()->orderByDesc('published_at')->limit($limit)])
                ->get()
                ->pluck('posts')
                ->flatten();

            return $region;
        });
    }
}
