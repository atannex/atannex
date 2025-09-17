<?php

declare(strict_types=1);

namespace App\Enums\Traits;

use OutOfBoundsException;
use App\Enums\Entity;

/**
 * Trait HasEntityMapping
 *
 * Provides a mapping mechanism for entities to their respective methods and ID keys.
 * This trait centralizes the configuration for entity-to-method mappings, enabling
 * dynamic resolution of methods and ID key requirements based on entity types.
 */
trait HasEntityMapping
{
    /**
     * @var array $MAPPINGS
     *
     * A static associative array that maps entity types to their configurations.
     * Each mapping includes the entity identifier, the associated method name,
     * and an optional ID key for entities that require specific identifiers.
     */
    private static array $MAPPINGS = [
        Entity::POST_BY_CATEGORY => [
            'entity' => Entity::POST_BY_CATEGORY,
            'method' => 'getPostsForCategory',
            'idKey'  => 'category_id',
        ],
        Entity::POST_BY_REGION => [
            'entity' => Entity::POST_BY_REGION,
            'method' => 'getPostsForRegion',
            'idKey'  => 'region_id',
        ],
        Entity::POST_BY_TAG => [
            'entity' => Entity::POST_BY_TAG,
            'method' => 'getPostsByTag',
            'idKey'  => 'tag_id',
        ],
        Entity::GET_CATEGORY_WITH_POSTS => [
            'entity' => Entity::GET_CATEGORY_WITH_POSTS,
            'method' => 'getCategoryWithPosts',
            'idKey'  => 'posts_with_id',
        ],
        Entity::GET_REGION_WITH_POSTS => [
            'entity' => Entity::GET_REGION_WITH_POSTS,
            'method' => 'getRegionWithPosts',
            'idKey'  => 'posts_with_id',
        ],
        Entity::GET_TAG_WITH_POSTS => [
            'entity' => Entity::GET_TAG_WITH_POSTS,
            'method' => 'getTagWithPosts',
            'idKey'  => 'posts_with_id',
        ],
        Entity::BREAKING_POSTS => [
            'entity' => Entity::BREAKING_POSTS,
            'method' => 'getBreakingPosts',
        ],
    ];

    /**
     * Retrieves the mapping configuration for a given entity.
     *
     * @param string $entity The entity type to retrieve the mapping for.
     * @return array The mapping configuration for the specified entity.
     * @throws OutOfBoundsException If the entity is not found in the mappings.
     */
    public static function getMapping(string $entity): array
    {
        return self::$MAPPINGS[$entity];
    }

    /**
     * Resolves the method name associated with a given entity.
     *
     * @param string $entity The entity type to resolve the method for.
     * @return string The method name associated with the entity.
     * @throws OutOfBoundsException If the entity is not found in the mappings.
     */
    public static function resolveMethod(string $entity): string
    {
        return self::$MAPPINGS[$entity]['method'];
    }

    /**
     * Checks if the given entity requires an ID key in its mapping.
     *
     * @param string $entity The entity type to check.
     * @return bool True if the entity requires an ID key, false otherwise.
     */
    public static function requiresIdKey(string $entity): bool
    {
        return array_key_exists('idKey', self::$MAPPINGS[$entity]);
    }
}
