<?php

namespace Atannex\Views\Traits;

use Illuminate\Support\Collection;

trait Entities
{
    /**
     * Resolve entities based on type and IDs, retrieving corresponding posts.
     *
     * @param string|null $type Entity type (tag_and_post, region_and_post, category_and_post)
     * @param array<int|string> $ids Array of entity IDs
     * @param int|null $limit Maximum number of results
     * @return Collection
     */
    private function resolveEntities(?string $type, array $ids, ?int $limit): Collection
    {
        if (empty($type) || empty($ids)) {
            return collect();
        }

        $resolverMap = [
            'tag_and_post' => [
                'method' => 'getTagWithPosts',
                'key'    => 'tag_id',
            ],
            'region_and_post' => [
                'method' => 'getFondomWithPosts',
                'key'    => 'region_id',
            ],
            'category_and_post' => [
                'method' => 'getCategoryWithPosts',
                'key'    => 'category_id',
            ],
        ];

        if (!array_key_exists($type, $resolverMap)) {
            return collect();
        }

        $resolver = $resolverMap[$type];
        $params = [
            $resolver['key'] => $ids,
        ];

        if (!is_null($limit) && $limit > 0) {
            $params['limit'] = $limit;
        }

        return $this->getComponent->{$resolver['method']}($params) ?? collect();
    }
}
