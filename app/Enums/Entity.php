<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Attributes\Description;

/**
 * Entity Enum
 *
 * Represents different post retrieval or display contexts
 * used across the system.
 */
final class Entity extends Enum
{
    #[Description('Breaking Posts')]
    public const BREAKING_POSTS = 'breaking-posts';

    #[Description('Posts by Category')]
    public const POSTS_BY_CATEGORY = 'post-by-category';

    #[Description('Posts by Region')]
    public const POSTS_BY_REGION = 'post-by-region';

    #[Description('Posts by Tag')]
    public const POSTS_BY_TAG = 'post-by-tag';

    #[Description('Categories with Posts')]
    public const CATEGORIES_WITH_POSTS = 'category-with-posts';

    #[Description('Regions with Posts')]
    public const REGIONS_WITH_POSTS = 'region-with-posts';
}
