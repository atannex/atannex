<?php

namespace Atannex\Views\Traits;

use App\Enums\PostType;
use App\Models\Pages\Category;
use App\Models\Regions\Region;

trait Entities
{
    private const ENTITY_MAPPING = [
        PostType::POST_BY_FONDOM => [
            'entity' => 'region',
            'idKey' => 'fondom_region_id',
        ],
        PostType::POST_BY_SUBDIVISION => [
            'entity' => 'region',
            'idKey' => 'subdivision_region_id',
        ],
        PostType::POST_BY_CATEGORY => [
            'entity' => 'category',
            'idKey' => 'category_id',
        ],
    ];

    /**
     * Get entity mapping for a given PostType.
     *
     * @return array{entity: string, idKey: string}|null
     */
    private function getEntityMapping(PostType $type): ?array
    {
        return self::ENTITY_MAPPING[$type] ?? null;
    }


    /**
     * Extract entity type and IDs from a tab configuration.
     */
    private function extractEntityAndIds(array $tabConfig): array
    {
        $type = $tabConfig['type'] ?? null;
        if (!isset(self::ENTITY_MAPPING[$type])) {
            return [null, []];
        }

        $mapping = self::ENTITY_MAPPING[$type];
        $ids = (array) ($tabConfig[$mapping['idKey']] ?? []);

        return [$mapping['entity'], $ids];
    }

    /**
     * Resolve entities by type and IDs, optionally applying a limit.
     */
    private function resolveEntities(?string $type, array $ids = [], int $limit = 0)
    {
        if (!$type || $ids === []) {
            return null;
        }

        $query = match ($type) {
            'region' => Region::with('posts')->whereIn('id', $ids)->latest('created_at'),
            'category' => Category::with('posts')->whereIn('id', $ids)->latest('created_at'),
            default => null,
        };

        if (!$query) {
            return null;
        }

        return $limit > 0 ? $query->limit($limit)->get() : $query->get();
    }
}
