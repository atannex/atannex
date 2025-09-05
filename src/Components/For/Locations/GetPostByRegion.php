<?php

namespace Atannex\Components\For\Locations;

use App\Models\Posts\Post;
use App\Models\Regions\Region;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

trait GetPostByRegion
{
    public function getPostsForRegion(array $config = []): Collection
    {
        $regionIds        = (array) ($config['region_id'] ?? []);
        $limit            = max(1, (int) ($config['limit'] ?? 15));
        $limitPerLeafPost = max(1, (int) ($config['limit_per_leaf_post'] ?? 1));

        $sortBy  = $config['sort'] ?? 'published_at';
        $sortDir = $config['order'] ?? 'desc';

        if ($regionIds === []) {
            return collect();
        }

        $regions = Region::with('children')->whereIn('id', $regionIds)->get();
        $allPosts = collect();

        foreach ($regions as $region) {
            if ($region->children->isEmpty()) {

                $posts = Post::published()
                    ->whereHas('regions', fn(Builder $q) => $q->where('regions.id', $region->id))
                    ->orderBy($sortBy, $sortDir)
                    ->take($limit)
                    ->get();

                $allPosts = $allPosts->merge($posts);
            } else {

                $leafIds = $region->getDescendantsAndSelf('dfs')
                    ->filter(fn($r) => $r->children->isEmpty())
                    ->pluck('id')
                    ->all();

                if (empty($leafIds)) {
                    continue;
                }

                $posts = Post::published()
                    ->whereHas('regions', fn(Builder $q) => $q->whereIn('regions.id', $leafIds))
                    ->orderBy($sortBy, $sortDir)
                    ->get()
                    ->groupBy(fn(Post $p) => $p->regions->first()->id)
                    ->flatMap(fn($group) => $group->take($limitPerLeafPost));

                $allPosts = $allPosts->merge($posts);
            }
        }

        return $allPosts
            ->unique('id')
            ->sortBy([$sortBy => $sortDir === 'desc' ? SORT_DESC : SORT_ASC])
            ->values();
    }
}
