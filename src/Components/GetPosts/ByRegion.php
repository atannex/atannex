<?php

namespace Atannex\Components\GetPosts;

use App\Models\Regions\Region;
use Atannex\Traits\HasPostsForHierarchy;
use Illuminate\Support\Collection;

trait ByRegion
{
    use HasPostsForHierarchy;

    public function getPostsForRegion(array $config = []): Collection
    {
        $regionIds = (array) ($config['region_id']);

        return $this->fetchPostsForHierarchy(
            $regionIds,
            $config,
            Region::class,
            'regions',
            null,
            fn ($region) => $region->getDescendants()
                ->filter(fn ($r) => $r->children->isEmpty())
                ->pluck('id')
                ->all(),
            fn ($post) => $post->regions->first()->id
        );
    }
}
