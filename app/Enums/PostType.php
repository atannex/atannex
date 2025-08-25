<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use Atannex\Filters\GetEnum;

final class PostType extends Enum
{
    use GetEnum;

    public const BREAKING_POST = 'breaking-post';

    public const EDITOR_PICK = 'editor-pick';

    public const EDITOR_WEEKLY_PICK = 'editor-weekly-pick';

    public const FEATURED_POST = 'featured-post';

    public const HEADLINE_OF_THE_DAY = 'headline-of-the-day';

    public const HOT_PICKS_POST = 'hot-picks-post';

    public const JUST_PUBLISHED_POST = 'just-published-post';

    public const LATEST_POST_IN_CATEGORY = 'latest-post-in-category';

    public const MOST_COMMENTED_POST = 'most-commented-post';

    public const MOST_ENGAGED_POST = 'most-engaged-post';

    public const MOST_LIKED_POST = 'most-liked-post';

    public const MOST_READ_POST = 'most-read-post';

    public const MOST_SHARED_POST = 'most-shared-post';

    public const MOST_VIEWED_AND_COMMENTED_POST = 'most-viewed-and-commented-post';

    public const MOST_VIEWED_AND_LIKED_POST = 'most-viewed-and-liked-post';

    public const MOST_VIEWED_AND_SHARED_POST = 'most-viewed-and-shared-post';

    public const MOST_VIEWED_POST = 'most-viewed-post';

    public const MOST_VIEWED_POST_THIS_WEEK = 'most-viewed-post-this-week';

    public const MOST_VIEWED_POST_TODAY = 'most-viewed-post-today';

    public const MOST_VIEWED_SHARED_LIKED_AND_COMMENTED_POST = 'most-viewed-shared-liked-and-commented-post';

    public const POPULAR_POST = 'popular-post';

    public const POPULAR_POST_IN_CATEGORY = 'popular-post-in-category';

    public const POST_BY_AUTHOR = 'post-by-author';

    public const POST_BY_CATEGORY = 'post-by-category';

    public const POST_BY_DIVISION = 'post-by-division';

    public const POST_BY_FONDOM = 'post-by-fondom';

    public const POST_BY_MONTH = 'post-by-month';

    public const POST_BY_SUBDIVISION = 'post-by-subdivision';

    public const POST_BY_TAG = 'post-by-tag';

    public const POST_BY_TODAY = 'post-by-today';

    public const POST_BY_TWO_WEEKS = 'post-by-two-weeks';

    public const POST_BY_VILLAGE = 'post-by-village';

    public const POST_BY_WEEK = 'post-by-week';

    public const RECENT_POST = 'recent-post';

    public const THIS_WEEK_TOP_POST = 'this-week-top-post';

    public const TODAY_STORIES = 'today-stories';

    public const TOP_POST_IN_TAG = 'top-post-in-tag';

    public const TOP_RATED_POST = 'top-rated-post';

    public const TRENDING_POST = 'trending-post';

    public const TRENDING_POST_IN_TAG = 'trending-post-in-tag';

    public static function boot(): void
    {
        static::setMetadata([
            self::BREAKING_POST => [
                'label' => 'Breaking News',
                'description' => 'Urgent and time-sensitive posts.',
                'color' => 'red',
                'icon' => 'heroicon-o-bolt',
            ],
            self::EDITOR_PICK => [
                'label' => "Editor's Pick",
                'description' => 'Highlighted by editors as top-quality content.',
                'color' => 'blue',
                'icon' => 'heroicon-o-star',
            ],
            self::EDITOR_WEEKLY_PICK => [
                'label' => "Weekly Editor's Pick",
                'description' => 'Best editor-picked post of the week.',
                'color' => 'blue',
                'icon' => 'heroicon-o-calendar',
            ],
            self::FEATURED_POST => [
                'label' => 'Featured Post',
                'description' => 'Highlighted on homepage or section.',
                'color' => 'purple',
                'icon' => 'heroicon-o-sparkles',
            ],
            self::HEADLINE_OF_THE_DAY => [
                'label' => 'Headline of the Day',
                'description' => 'Top story for the current day.',
                'color' => 'cyan',
                'icon' => 'heroicon-o-newspaper',
            ],
            self::HOT_PICKS_POST => [
                'label' => 'Hot Picks',
                'description' => 'Popular and trending content.',
                'color' => 'orange',
                'icon' => 'heroicon-o-fire',
            ],
            self::JUST_PUBLISHED_POST => [
                'label' => 'Just Published',
                'description' => 'Recently added to the platform.',
                'color' => 'emerald',
                'icon' => 'heroicon-o-clock',
            ],
            self::LATEST_POST_IN_CATEGORY => [
                'label' => 'Latest in Category',
                'description' => 'Newest post under a specific category.',
                'color' => 'indigo',
                'icon' => 'heroicon-o-folder-open',
            ],
            self::MOST_COMMENTED_POST => [
                'label' => 'Most Commented',
                'description' => 'Post with the most user comments.',
                'color' => 'yellow',
                'icon' => 'heroicon-o-chat-bubble-oval-left-ellipsis',
            ],
            self::MOST_ENGAGED_POST => [
                'label' => 'Most Engaged',
                'description' => 'Post with highest user interaction.',
                'color' => 'teal',
                'icon' => 'heroicon-o-users',
            ],
            self::MOST_LIKED_POST => [
                'label' => 'Most Liked',
                'description' => 'Post with the most likes.',
                'color' => 'rose',
                'icon' => 'heroicon-o-hand-thumb-up',
            ],
            self::MOST_READ_POST => [
                'label' => 'Most Read',
                'description' => 'Post with highest number of views.',
                'color' => 'lime',
                'icon' => 'heroicon-o-eye',
            ],
            self::MOST_SHARED_POST => [
                'label' => 'Most Shared',
                'description' => 'Post with the most shares on social media.',
                'color' => 'pink',
                'icon' => 'heroicon-o-share',
            ],
            self::MOST_VIEWED_AND_COMMENTED_POST => [
                'label' => 'Most Viewed & Commented',
                'description' => 'Highly viewed and discussed post.',
                'color' => 'blue',
                'icon' => 'heroicon-o-chat-bubble-left-right',
            ],
            self::MOST_VIEWED_AND_LIKED_POST => [
                'label' => 'Most Viewed & Liked',
                'description' => 'Popular and well-rated post.',
                'color' => 'green',
                'icon' => 'heroicon-o-heart',
            ],
            self::MOST_VIEWED_AND_SHARED_POST => [
                'label' => 'Most Viewed & Shared',
                'description' => 'Trending post widely shared.',
                'color' => 'fuchsia',
                'icon' => 'heroicon-o-paper-airplane',
            ],
            self::MOST_VIEWED_POST => [
                'label' => 'Most Viewed',
                'description' => 'Top viewed post overall.',
                'color' => 'indigo',
                'icon' => 'heroicon-o-chart-bar',
            ],
            self::MOST_VIEWED_POST_THIS_WEEK => [
                'label' => 'Most Viewed This Week',
                'description' => 'Top views in current week.',
                'color' => 'blue',
                'icon' => 'heroicon-o-calendar-days',
            ],
            self::MOST_VIEWED_POST_TODAY => [
                'label' => 'Most Viewed Today',
                'description' => 'Top views in the last 24 hours.',
                'color' => 'teal',
                'icon' => 'heroicon-o-sun',
            ],
            self::MOST_VIEWED_SHARED_LIKED_AND_COMMENTED_POST => [
                'label' => 'All-in-One Post',
                'description' => 'Top viewed, shared, liked, and commented post.',
                'color' => 'amber',
                'icon' => 'heroicon-o-trophy',
            ],
            self::POPULAR_POST => [
                'label' => 'Popular Post',
                'description' => 'Trending across the site.',
                'color' => 'pink',
                'icon' => 'heroicon-o-trending-up',
            ],
            self::POPULAR_POST_IN_CATEGORY => [
                'label' => 'Popular in Category',
                'description' => 'Trending in a specific category.',
                'color' => 'violet',
                'icon' => 'heroicon-o-tag',
            ],
            self::POST_BY_AUTHOR => [
                'label' => 'Post by Author',
                'description' => 'Posts grouped by author.',
                'color' => 'gray',
                'icon' => 'heroicon-o-user',
            ],
            self::POST_BY_CATEGORY => [
                'label' => 'Post by Category',
                'description' => 'Posts grouped by category.',
                'color' => 'gray',
                'icon' => 'heroicon-o-folder',
            ],
            self::POST_BY_DIVISION => [
                'label' => 'Post by Division',
                'description' => 'Posts from a specific division.',
                'color' => 'gray',
                'icon' => 'heroicon-o-map',
            ],
            self::POST_BY_FONDOM => [
                'label' => 'Post by Fondom',
                'description' => 'Posts from a traditional fondom.',
                'color' => 'gray',
                'icon' => 'heroicon-o-home-modern',
            ],
            self::POST_BY_MONTH => [
                'label' => 'Post by Month',
                'description' => 'Posts published within a specific month.',
                'color' => 'gray',
                'icon' => 'heroicon-o-calendar',
            ],
            self::POST_BY_SUBDIVISION => [
                'label' => 'Post by Subdivision',
                'description' => 'Posts grouped by subdivision.',
                'color' => 'gray',
                'icon' => 'heroicon-o-building-office',
            ],
            self::POST_BY_TAG => [
                'label' => 'Post by Tag',
                'description' => 'Posts tagged with specific keywords.',
                'color' => 'gray',
                'icon' => 'heroicon-o-hashtag',
            ],
            self::POST_BY_TODAY => [
                'label' => "Today's Posts",
                'description' => 'Posts created today.',
                'color' => 'green',
                'icon' => 'heroicon-o-calendar-clock',
            ],
            self::POST_BY_TWO_WEEKS => [
                'label' => 'Last 2 Weeks',
                'description' => 'Posts published in the last 14 days.',
                'color' => 'green',
                'icon' => 'heroicon-o-clock',
            ],
            self::POST_BY_VILLAGE => [
                'label' => 'Post by Village',
                'description' => 'Posts grouped by village.',
                'color' => 'gray',
                'icon' => 'heroicon-o-home',
            ],
            self::POST_BY_WEEK => [
                'label' => 'Post by Week',
                'description' => 'Posts from a given week.',
                'color' => 'gray',
                'icon' => 'heroicon-o-calendar-days',
            ],
            self::RECENT_POST => [
                'label' => 'Recent Post',
                'description' => 'Freshly published content.',
                'color' => 'cyan',
                'icon' => 'heroicon-o-clock',
            ],
            self::THIS_WEEK_TOP_POST => [
                'label' => 'Top This Week',
                'description' => 'Highest rated post of the week.',
                'color' => 'yellow',
                'icon' => 'heroicon-o-trophy',
            ],
            self::TODAY_STORIES => [
                'label' => "Today's Stories",
                'description' => 'All published stories for today.',
                'color' => 'emerald',
                'icon' => 'heroicon-o-book-open',
            ],
            self::TOP_POST_IN_TAG => [
                'label' => 'Top in Tag',
                'description' => 'Top-performing post within a tag.',
                'color' => 'fuchsia',
                'icon' => 'heroicon-o-hashtag',
            ],
            self::TOP_RATED_POST => [
                'label' => 'Top Rated',
                'description' => 'Posts with the best ratings.',
                'color' => 'amber',
                'icon' => 'heroicon-o-star',
            ],
            self::TRENDING_POST => [
                'label' => 'Trending Post',
                'description' => 'Currently trending across the platform.',
                'color' => 'rose',
                'icon' => 'heroicon-o-chart-line',
            ],
            self::TRENDING_POST_IN_TAG => [
                'label' => 'Trending in Tag',
                'description' => 'Trending post within a tag.',
                'color' => 'rose',
                'icon' => 'heroicon-o-hashtag',
            ],
        ]);
    }
}
