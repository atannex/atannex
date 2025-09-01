<?php

namespace Atannex\Views\Traits;

use Illuminate\Support\Collection;

trait Entities
{
    /**
     * Resolve entities based on type, IDs, and full config.
     */
    private function resolveEntities(?string $type, array $ids, array $config = []): Collection
    {
        if (empty($type) || empty($ids)) {
            return collect();
        }

        $resolverMap = [
            'tag_and_post'      => ['method' => 'getTagWithPosts',      'key' => 'tag_id'],
            'region_and_post'   => ['method' => 'getFondomWithPosts',   'key' => 'region_id'],
            'category_and_post' => ['method' => 'getCategoryWithPosts', 'key' => 'category_and_post_id'],
        ];

        if (!isset($resolverMap[$type])) {
            return collect();
        }

        $resolver = $resolverMap[$type];
        $config[$resolver['key']] = $ids;

        return $this->getComponent->{$resolver['method']}($config) ?? collect();
    }
}
