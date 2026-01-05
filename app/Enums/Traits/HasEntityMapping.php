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
     * Retrieve the mapping sub-array for the specified entity.
     *
     * @param string $entity The entity key to look up in the mappings.
     * @return array<string,mixed> The mapping sub-array associated with the entity.
     */
    public static function getMapping(string $entity): array
    {
        return self::$mappings[$entity];
    }

    /**
     * Get the configured handler method name for the specified entity.
     *
     * Assumes the provided entity key exists in the internal mappings.
     *
     * @param string $entity The entity mapping key.
     * @return string The method name associated with the entity.
     */
    public static function resolveMethod(string $entity): string
    {
        return self::$mappings[$entity]['method'];
    }

    /**
     * Determine whether a mapping for the given entity includes an identifier key.
     *
     * @param string $entity The mapping key identifying the entity.
     * @return bool `true` if the entity's mapping contains an `idKey`, `false` otherwise.
     */
    public static function requiresIdKey(string $entity): bool
    {
        return isset(self::$mappings[$entity]['idKey']);
    }
}