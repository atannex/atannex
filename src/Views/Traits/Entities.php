<?php

namespace Atannex\Views\Traits;

use Illuminate\Support\Collection;

trait Entities
{
    /**
     * Resolve entities based on type and IDs, retrieving corresponding posts.
     *
     * @param string|null $type Entity type (tag, region, category)
     * @param array<int|string> $ids Array of entity IDs
     * @param int|null $limit Maximum number of results
     * @return Collection
     */
    private function resolveEntities(?string $type, array $ids, ?int $limit): Collection
    {
        if (!$type || !$ids) {
            return collect();
        }

        $resolverMap = [
            'tag_and_post' => [
                'method' => 'getTagAndTheirCorrespondingPosts',
                'key' => 'tag_id'
            ],
            'region_and_post' => [
                'method' => 'getFondomAndTheirCorrespondingPosts',
                'key' => 'region_id'
            ],
            'category_and_post' => [
                'method' => 'getCategoryAndTheirCorrespondingPosts',
                'key' => 'category_id'
            ],
        ];

        if (!isset($resolverMap[$type])) {
            return collect();
        }

        $params = [$resolverMap[$type]['key'] => $ids];
        if ($limit !== null && $limit > 0) {
            $params['limit'] = $limit;
        }

        $collection = $this->getComponent->{$resolverMap[$type]['method']}($params);

        return $limit > 0 ? $collection->take($limit) : $collection;
    }
}
