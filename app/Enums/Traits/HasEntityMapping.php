<?php

declare(strict_types=1);

namespace App\Enums\Traits;

use App\Enums\Entity;

/**
 * Trait HasEntityMapping
 *
 * Provides a mapping mechanism for entities to their respective methods and ID keys.
 * Returns empty values when data is not available instead of throwing exceptions.
 */
trait HasEntityMapping
{
    /**
     * @var array<string, array<string, mixed>> $MAPPINGS
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
     * Retrieve the mapping configuration for a given entity.
     *
     * @param string|null $entity
     * @return array<string, mixed> Returns an empty array if entity not found.
     */
    public static function getMapping(?string $entity): array
    {
        if ($entity === null || !isset(self::$MAPPINGS[$entity])) {
            return [];
        }

        return self::$MAPPINGS[$entity];
    }

    /**
     * Resolve the method name for a given entity.
     *
     * @param string|null $entity
     * @return string Returns an empty string if method not found.
     */
    public static function resolveMethod(?string $entity): string
    {
        return self::$MAPPINGS[$entity]['method'] ?? '';
    }

    /**
     * Check if the given entity requires an ID key in its mapping.
     *
     * @param string|null $entity
     * @return bool Returns false if entity is null or not mapped.
     */
    public static function requiresIdKey(?string $entity): bool
    {
        return isset(self::$MAPPINGS[$entity]['idKey']);
    }
}
