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
        if ($type === null || $type === '' || $type === '0' || $ids === []) {
            return collect();
        }

        $resolverMap = [
            'tag_with_posts' => [
                'method' => 'getTagWithPosts',
                'key'    => 'tag_with_post_id',
            ],
            'region_with_posts' => [
                'method' => 'getRegionWithPosts',
                'key'    => 'region_with_post_id',
            ],

            'category_with_posts' => [
                'method' => 'getCategoryWithPosts',
                'key'    => 'category_with_post_id',
            ],
        ];

        if (!isset($resolverMap[$type])) {
            return collect();
        }

        $resolver = $resolverMap[$type];
        $config[$resolver['key']] = $ids;

        return $this->getComponent->{$resolver['method']}($config) ?? collect();
    }
}
