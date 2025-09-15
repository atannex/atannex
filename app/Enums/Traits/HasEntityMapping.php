<?php

declare(strict_types=1);

namespace App\Enums\Traits;

use App\Enums\Entity;

trait HasEntityMapping
{
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

    public static function getMapping(string $entity): array
    {
        return self::$MAPPINGS[$entity];
    }

    public static function resolveMethod(string $entity): string
    {
        return self::$MAPPINGS[$entity]['method'];
    }

    public static function requiresIdKey(string $entity): bool
    {
        return array_key_exists('idKey', self::$MAPPINGS[$entity]);
    }
}
