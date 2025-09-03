<?php

namespace Atannex\Components\With;

use App\Models\Regions\Region;
use Illuminate\Support\Collection;

/**
 * Trait GetFondomWithPosts
 *
 * Provides a method to retrieve fondoms (regions of type "Fondom")
 * along with their latest posts.
 */
trait GetFondomWithPosts
{
    /**
     * Retrieve fondoms with their associated posts.
     *
     * @param array{
     *     region_id: array<int>,
     *     limit?: int,
     *     post_limit?: int
     * } $config
     *
     * @return Collection<int, Region>
     */
    public function getFondomWithPosts(array $config): Collection
    {
        $regionIds = $config['region_id'] ?? [];
        $limit     = $config['limit'] ?? 5;
        $postLimit = $config['post_limit'] ?? 5;

        if (empty($regionIds)) {
            return collect();
        }

        return Region::with([
                'posts' => function ($query) use ($postLimit) {
                    $query->latest('created_at')->take($postLimit);
                },
            ])
            ->whereIn('id', $regionIds)
            ->where('type', 'Fondom')
            ->latest('created_at')
            ->take($limit)
            ->get();
    }
}
