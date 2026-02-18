<?php

declare(strict_types=1);

namespace App\Enums\Traits;

use App\Enums\Entity;

/**
 * Trait HasEntityMapping
 *
 * Provides a mapping mechanism for entities to their respective methods and ID keys.
 */
trait HasEntityMapping
{
    private static array $mappings = [

        Entity::BREAKING_POSTS => [
            'entity' => Entity::BREAKING_POSTS,
            'method' => 'getBreakingPosts',
        ],
        Entity::RECENT_POSTS => [
            'entity' => Entity::RECENT_POSTS,
            'method' => 'getRecentPosts',
        ],
        Entity::RANDOM_POSTS => [
            'entity' => Entity::RANDOM_POSTS,
            'method' => 'getRandomPosts',
        ],
        Entity::FEATURED_POSTS => [
            'entity' => Entity::FEATURED_POSTS,
            'method' => 'getFeaturedPosts',
        ],
        Entity::EDITORS_PICKS => [
            'entity' => Entity::EDITORS_PICKS,
            'method' => 'getEditorsPicks',
        ],

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

        Entity::POSTS_BY_AUTHOR => [
            'entity' => Entity::POSTS_BY_AUTHOR,
            'method' => 'getPostsByAuthor',
            'idKey' => 'author_id',
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

        Entity::MOST_VIEWED_POSTS => [
            'entity' => Entity::MOST_VIEWED_POSTS,
            'method' => 'getMostViewedPosts',
        ],

        Entity::MOST_COMMENTED_POSTS => [
            'entity' => Entity::MOST_COMMENTED_POSTS,
            'method' => 'getMostCommentedPosts',
        ],

        Entity::MOST_SHARED_POSTS => [
            'entity' => Entity::MOST_SHARED_POSTS,
            'method' => 'getMostSharedPosts',
        ],

        Entity::MOST_LIKED_POSTS => [
            'entity' => Entity::MOST_LIKED_POSTS,
            'method' => 'getMostLikedPosts',
        ],

        Entity::TOP_RATED_POSTS => [
            'entity' => Entity::TOP_RATED_POSTS,
            'method' => 'getTopRatedPosts',
        ],

        Entity::TRENDING_POSTS => [
            'entity' => Entity::TRENDING_POSTS,
            'method' => 'getTrendingPosts',
        ],

        Entity::POPULAR_THIS_WEEK => [
            'entity' => Entity::POPULAR_THIS_WEEK,
            'method' => 'getPopularPostsThisWeek',
        ],

        Entity::POPULAR_THIS_MONTH => [
            'entity' => Entity::POPULAR_THIS_MONTH,
            'method' => 'getPopularPostsThisMonth',
        ],

        Entity::WEEKLY_HIGHLIGHTS => [
            'entity' => Entity::WEEKLY_HIGHLIGHTS,
            'method' => 'getWeeklyHighlights',
        ],

        Entity::MONTHLY_HIGHLIGHTS => [
            'entity' => Entity::MONTHLY_HIGHLIGHTS,
            'method' => 'getMonthlyHighlights',
        ],

        Entity::TRENDING_THIS_WEEK => [
            'entity' => Entity::TRENDING_THIS_WEEK,
            'method' => 'getTrendingThisWeek',
        ],

        Entity::TRENDING_THIS_MONTH => [
            'entity' => Entity::TRENDING_THIS_MONTH,
            'method' => 'getTrendingThisMonth',
        ],
    ];

    /**
     * Retrieve the mapping entry for a given entity key.
     *
     * @param  string  $entity  The entity key used to look up the mapping in the trait's internal mapping table.
     * @return array The mapping array containing keys such as 'entity', 'method', and an optional 'idKey'.
     */
    public static function getMapping(string $entity): array
    {
        return self::$mappings[$entity];
    }

    /**
     * Get the configured method name for a given entity mapping.
     *
     * @param  string  $entity  The mapping key identifying the entity.
     * @return string The method name associated with the specified entity.
     */
    public static function resolveMethod(string $entity): string
    {
        return self::$mappings[$entity]['method'];
    }

    /**
     * Determine whether the mapping for the given entity includes an `idKey`.
     *
     * @param  string  $entity  The mapping key identifying an entity in this trait's mapping table.
     * @return bool `true` if the entity's mapping defines an `idKey`, `false` otherwise.
     */
    public static function requiresIdKey(string $entity): bool
    {
        return isset(self::$mappings[$entity]['idKey']);
    }
}
