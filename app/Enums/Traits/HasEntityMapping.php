<?php

declare(strict_types=1);

namespace App\Enums\Traits;

use App\Enums\Entity;

/**
 * Trait HasEntityMapping
 *
 * Provides a mapping mechanism for entities to their respective methods and ID keys.
 * Assumes all entity data is valid.
 */
trait HasEntityMapping
{
    /**
     * Mapping configuration for entities.
     *
     * @var array<string, array<string, mixed>>
     */
    private static array $mappings = [
        Entity::POSTS_BY_CATEGORY => [
            'entity' => Entity::POSTS_BY_CATEGORY,
            'method' => 'getPostsForCategory',
            'idKey' => 'category_id',
        ],

        Entity::POSTS_BY_REGION => [
            'entity' => Entity::POSTS_BY_REGION,
            'method' => 'getPostsForRegion',
            'idKey' => 'region_id',
        ],

        Entity::POSTS_BY_TAG => [
            'entity' => Entity::POSTS_BY_TAG,
            'method' => 'getPostsByTag',
            'idKey' => 'tag_id',
        ],

        Entity::CATEGORIES_WITH_POSTS => [
            'entity' => Entity::CATEGORIES_WITH_POSTS,
            'method' => 'getCategoryWithPosts',
            'idKey' => 'posts_with_id',
        ],

        Entity::REGIONS_WITH_POSTS => [
            'entity' => Entity::REGIONS_WITH_POSTS,
            'method' => 'getRegionWithPosts',
            'idKey' => 'posts_with_id',
        ],

        Entity::BREAKING_POSTS => [
            'entity' => Entity::BREAKING_POSTS,
            'method' => 'getBreakingPosts',
        ],
    ];

    /**
     * Retrieve the mapping configuration for a given entity.
     *
     * @return array<string, mixed>
     */
    public static function getMapping(string $entity): array
    {
        return self::$mappings[$entity];
    }

    /**
     * Resolve the method name for a given entity.
     */
    public static function resolveMethod(string $entity): string
    {
        return self::$mappings[$entity]['method'];
    }

    /**
     * Check if the given entity requires an ID key in its mapping.
     */
    public static function requiresIdKey(string $entity): bool
    {
        return isset(self::$mappings[$entity]['idKey']);
    }
}
