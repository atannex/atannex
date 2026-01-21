<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Attributes\Description;

/**
 * Entity Enum
 *
 * User-facing ways to retrieve or view village news posts.
 */
final class Entity extends Enum
{
    #[Description('Breaking Posts')]
    public const BREAKING_POSTS = 'breaking-posts';

    #[Description('Latest Posts')]
    public const RECENT_POSTS = 'recent-posts';

    #[Description('Random Posts')]
    public const RANDOM_POSTS = 'random-posts';

    #[Description('Featured Posts')]
    public const FEATURED_POSTS = 'featured-posts';

    #[Description("Editor's Picks")]
    public const EDITORS_PICKS = 'editors-picks';

    #[Description('Posts by Category')]
    public const POSTS_BY_CATEGORY = 'posts-by-category';

    #[Description('Posts by Region')]
    public const POSTS_BY_REGION = 'posts-by-region';

    #[Description('Posts by Author')]
    public const POSTS_BY_AUTHOR = 'posts-by-author';

    #[Description('Categories with Posts')]
    public const CATEGORIES_WITH_POSTS = 'categories-with-posts';

    #[Description('Regions with Posts')]
    public const REGIONS_WITH_POSTS = 'regions-with-posts';

    #[Description('Most Viewed')]
    public const MOST_VIEWED_POSTS = 'most-viewed-posts';

    #[Description('Most Commented')]
    public const MOST_COMMENTED_POSTS = 'most-commented-posts';

    #[Description('Most Shared')]
    public const MOST_SHARED_POSTS = 'most-shared-posts';

    #[Description('Most Liked')]
    public const MOST_LIKED_POSTS = 'most-liked-posts';

    #[Description('Top Rated')]
    public const TOP_RATED_POSTS = 'top-rated-posts';

    #[Description('Trending Posts')]
    public const TRENDING_POSTS = 'trending-posts';

    #[Description('Popular This Week')]
    public const POPULAR_THIS_WEEK = 'popular-this-week';

    #[Description('Popular This Month')]
    public const POPULAR_THIS_MONTH = 'popular-this-month';

    #[Description('Weekly Highlights')]
    public const WEEKLY_HIGHLIGHTS = 'weekly-highlights';

    #[Description('Monthly Highlights')]
    public const MONTHLY_HIGHLIGHTS = 'monthly-highlights';

    #[Description('Trending This Week')]
    public const TRENDING_THIS_WEEK = 'trending-this-week';

    #[Description('Trending This Month')]
    public const TRENDING_THIS_MONTH = 'trending-this-month';
}
