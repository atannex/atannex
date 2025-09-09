<?php

declare(strict_types=1);

namespace App\Enums;

use Atannex\Filters\GetEnum;
use BenSampo\Enum\Enum;

final class PostType extends Enum
{
    use GetEnum;

    public const BREAKING_POSTS = 'breaking-posts';

    public const GET_CATEGORY_WITH_POSTS = 'category-with-posts';

    public const GET_TAG_WITH_POSTS = 'tag-with-posts';

    public const GET_REGION_WITH_POSTS = 'region-with-posts';

    public const POST_BY_CATEGORY = 'post-by-category';

    public const POST_BY_REGION = 'post-by-region';

    public const POST_BY_TAG = 'post-by-tag';

    /**
     * Boot method to set metadata for each PostType.
     */
    public static function boot(): void
    {
        static::setMetadata([

            self::BREAKING_POSTS => [
                'label' => 'Breaking News',
                'description' => 'Breaking News.',
                'color' => 'emerald',
                'icon' => 'heroicon-o-clock',
            ],

            self::POST_BY_CATEGORY => [
                'label' => 'Post by Category',
                'description' => 'Posts grouped by category.',
                'color' => 'gray',
                'icon' => 'heroicon-o-folder',
            ],

            self::POST_BY_REGION => [
                'label' => 'Post by Region',
                'description' => 'Posts from a traditional region.',
                'color' => 'gray',
                'icon' => 'heroicon-o-home-modern',
            ],

            self::POST_BY_TAG => [
                'label' => 'Post by Tag',
                'description' => 'Posts tagged with specific keywords.',
                'color' => 'gray',
                'icon' => 'heroicon-o-hashtag',
            ],

            self::GET_CATEGORY_WITH_POSTS => [
                'label' => 'Get Category and its Corresponding Posts',
                'description' => 'Retrieve all posts under the selected Category.',
                'color' => 'rose',
                'icon' => 'heroicon-o-collection',
            ],

            self::GET_REGION_WITH_POSTS => [
                'label' => 'Get Region and its Corresponding Posts',
                'description' => 'Retrieve all posts under the selected region.',
                'color' => 'emerald',
                'icon' => 'heroicon-o-map',
            ],

            self::GET_TAG_WITH_POSTS => [
                'label' => 'Get Tag and its Corresponding Posts',
                'description' => 'Retrieve all posts associated with the selected Tag.',
                'color' => 'indigo',
                'icon' => 'heroicon-o-hashtag',
            ],
        ]);
    }

    /**
     * Entity mapping with explicit method names.
     * This is used by the Entities trait to resolve data dynamically.
     */
    private const ENTITY_MAPPING = [

        self::BREAKING_POSTS => [
            'entity' => 'breaking-posts',
            'idKey'  => null,
            'method' => 'getBreakingPosts',
        ],

        self::POST_BY_REGION => [
            'entity' => 'post-by-region',
            'idKey'  => 'region_id',
            'method' => 'getPostsForRegion',
        ],

        self::POST_BY_CATEGORY => [
            'entity' => 'post-by-category',
            'idKey'  => 'category_id',
            'method' => 'getPostsForCategory',
        ],

        self::POST_BY_TAG => [
            'entity' => 'post-by-tag',
            'idKey'  => 'tag_id',
            'method' => 'getPostByTag',
        ],

        self::GET_TAG_WITH_POSTS => [
            'entity' => 'tag-with-posts',
            'idKey'  => 'posts_with_id',
            'method' => 'getTagWithPosts',
        ],

        self::GET_REGION_WITH_POSTS => [
            'entity' => 'region-with-posts',
            'idKey'  => 'posts_with_id',
            'method' => 'getRegionWithPosts',
        ],

        self::GET_CATEGORY_WITH_POSTS => [
            'entity' => 'category-with-posts',
            'idKey'  => 'posts_with_id',
            'method' => 'getCategoryWithPosts',
        ],

    ];

    /**
     * Retrieve entity mapping for a given post type.
     *
     * @param string $type PostType constant
     * @return array{entity: string, idKey: string, method: string}|null
     */
    public static function getEntityMapping(string $type): ?array
    {
        return self::ENTITY_MAPPING[$type] ?? null;
    }
}
