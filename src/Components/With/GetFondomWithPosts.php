<?php

namespace Atannex\Components\With;

use App\Models\Regions\Region;
use Illuminate\Support\Collection;

trait GetFondomWithPosts
{
    public function getFondomWithPosts(array $config): Collection
    {

        $regionIds = $config['region_id'];
        $limit = $config['limit'] ?? 5;
        $post_limit = $config['post_limit'] ?? 5;

        return Region::with(['posts' => function ($query) use ($post_limit) {
                $query->latest('created_at')->take($post_limit);
            }])
            ->whereIn('id', $regionIds)
            ->where('type', 'Fondom')
            ->latest('created_at')
            ->take($limit)
            ->get();
    }
}
