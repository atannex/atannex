<?php

namespace Atannex\Views\Traits;

use Illuminate\Support\Collection;

/**
 * Trait Entities
 *
 * Provides entity resolution logic for different model types with posts.
 */
trait Entities
{
    /**
     * Resolve entities based on type, IDs, and configuration.
     *
     * @param string|null $type   The entity type identifier.
     * @param array<int, int|string> $ids   The entity IDs to resolve.
     * @param array<string, mixed> $config  Additional configuration options.
     *
     * @return Collection<int, mixed>
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
            'fondom_with_posts' => [
                'method' => 'getFondomWithPosts',
                'key'    => 'region_with_post_id',
            ],
            'sub_division_with_posts' => [
                'method' => 'getSubDivisionWithPosts',
                'key'    => 'region_with_post_id',
            ],
            'category_with_posts' => [
                'method' => 'getCategoryWithPosts',
                'key'    => 'category_with_post_id',
            ],
        ];

        if (!array_key_exists($type, $resolverMap)) {
            return collect();
        }

        $resolver = $resolverMap[$type];
        $config[$resolver['key']] = $ids;

        return $this->getComponent->{$resolver['method']}($config) ?? collect();
    }
}
