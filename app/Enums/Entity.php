<?php

declare(strict_types=1);

namespace App\Enums;

use Atannex\Filters\GetEnum;
use BenSampo\Enum\Enum;

/**
 * Enum representing different types of posts.
 */
final class Entity extends Enum
{
    use GetEnum;

    public const BREAKING_POSTS = 'breaking-posts';

    public const POST_BY_CATEGORY = 'post-by-category';

    public const POST_BY_REGION = 'post-by-region';

    public const POST_BY_TAG = 'post-by-tag';

    public const GET_CATEGORY_WITH_POSTS = 'category-with-posts';

    public const GET_REGION_WITH_POSTS = 'region-with-posts';

    public const GET_TAG_WITH_POSTS = 'tag-with-posts';

    /**
     * Metadata for each post type, defining display properties.
     *
     * @var array<string, array{label: string, description: string, color: string, icon: string}>
     */
    private const METADATA = [
        self::BREAKING_POSTS => [
            'label' => 'Breaking News',
            'description' => 'Urgent and critical news updates.',
            'color' => 'emerald',
            'icon' => 'heroicon-o-bolt',
        ],
        self::POST_BY_CATEGORY => [
            'label' => 'Posts by Category',
            'description' => 'Posts organized by specific categories.',
            'color' => 'gray',
            'icon' => 'heroicon-o-folder',
        ],
        self::POST_BY_REGION => [
            'label' => 'Posts by Region',
            'description' => 'Posts associated with specific geographic regions.',
            'color' => 'gray',
            'icon' => 'heroicon-o-map-pin',
        ],
        self::POST_BY_TAG => [
            'label' => 'Posts by Tag',
            'description' => 'Posts grouped by specific tags or keywords.',
            'color' => 'gray',
            'icon' => 'heroicon-o-tag',
        ],
        self::GET_CATEGORY_WITH_POSTS => [
            'label' => 'Category with Posts',
            'description' => 'Retrieve a category along with its associated posts.',
            'color' => 'rose',
            'icon' => 'heroicon-o-rectangle-group',
        ],
        self::GET_REGION_WITH_POSTS => [
            'label' => 'Region with Posts',
            'description' => 'Retrieve a region along with its associated posts.',
            'color' => 'emerald',
            'icon' => 'heroicon-o-globe-alt',
        ],
        self::GET_TAG_WITH_POSTS => [
            'label' => 'Tag with Posts',
            'description' => 'Retrieve a tag along with its associated posts.',
            'color' => 'indigo',
            'icon' => 'heroicon-o-bookmark',
        ],
    ];

    /**
     * Boot method to set metadata for each PostType.
     */
    public static function boot(): void
    {
        self::setMetadata(self::METADATA);
    }
}
