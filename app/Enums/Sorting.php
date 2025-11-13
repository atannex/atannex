<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * Sorting options tailored for a News website.
 *
 * @method static static PublishedAt()
 * @method static static CreatedAt()
 * @method static static UpdatedAt()
 * @method static static Views()
 * @method static static CommentsCount()
 * @method static static LikesCount()
 * @method static static SharesCount()
 * @method static static Rating()
 * @method static static Title()
 * @method static static Author()
 * @method static static Popularity()
 * @method static static Random()
 * @method static static Relevance()
 * @method static static Trending()
 * @method static static Featured()
 * @method static static Category()
 * @method static static ReadingTime()
 * @method static static BreakingPriority()
 * @method static static EditorPick()
 * @method static static SourceCredibility()
 * @method static static Region()
 * @method static static HeadlineLength()
 */
final class Sorting extends Enum
{
    const PUBLISHED_AT = 'published_at';

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    const VIEWS = 'views';

    const COMMENTS = 'comments';

    const LIKES = 'likes';

    const SHARES = 'shares';

    const RATING = 'rating';

    const TITLE = 'title';

    const AUTHOR = 'author';

    const POPULARITY = 'popularity';

    const RANDOM = 'random';

    const RELEVANCE = 'relevance';

    const TRENDING = 'trending';

    const FEATURED = 'featured';

    const CATEGORY = 'category';

    const READING_TIME = 'reading_time';

    const BREAKING_PRIORITY = 'breaking_priority';

    const EDITOR_PICK = 'editor_pick';

    const SOURCE_CREDIBILITY = 'source_credibility';

    const REGION = 'region';

    const HEADLINE_LENGTH = 'headline_length';

    /**
     * Return options formatted for Filament Select components.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::PUBLISHED_AT => 'Published Date',
            self::CREATED_AT => 'Creation Date',
            self::UPDATED_AT => 'Last Updated',
            self::VIEWS => 'Most Viewed',
            self::COMMENTS => 'Most Commented',
            self::LIKES => 'Most Liked',
            self::SHARES => 'Most Shared',
            self::RATING => 'Highest Rated',
            self::TITLE => 'Title (A–Z)',
            self::AUTHOR => 'Author',
            self::POPULARITY => 'Overall Popularity',
            self::RANDOM => 'Random Order',
            self::RELEVANCE => 'Search Relevance',
            self::TRENDING => 'Trending Now',
            self::FEATURED => 'Featured First',
            self::CATEGORY => 'Category',
            self::READING_TIME => 'Reading Time',
            self::BREAKING_PRIORITY => 'Breaking News Priority',
            self::EDITOR_PICK => 'Editor’s Pick',
            self::SOURCE_CREDIBILITY => 'Source Credibility',
            self::REGION => 'Region',
            self::HEADLINE_LENGTH => 'Headline Length',
        ];
    }

    /**
     * Return possible sort directions for each option.
     *
     * @return array<string, array<string>>
     */
    public static function sortDirections(): array
    {
        return [
            self::PUBLISHED_AT => ['asc', 'desc'],
            self::CREATED_AT => ['asc', 'desc'],
            self::UPDATED_AT => ['asc', 'desc'],
            self::VIEWS => ['asc', 'desc'],
            self::COMMENTS => ['asc', 'desc'],
            self::LIKES => ['asc', 'desc'],
            self::SHARES => ['asc', 'desc'],
            self::RATING => ['asc', 'desc'],
            self::TITLE => ['asc', 'desc'],
            self::AUTHOR => ['asc', 'desc'],
            self::POPULARITY => ['asc', 'desc'],
            self::RANDOM => [],
            self::RELEVANCE => ['asc', 'desc'],
            self::TRENDING => ['asc', 'desc'],
            self::FEATURED => ['asc', 'desc'],
            self::CATEGORY => ['asc', 'desc'],
            self::READING_TIME => ['asc', 'desc'],
            self::BREAKING_PRIORITY => ['asc', 'desc'],
            self::EDITOR_PICK => ['asc', 'desc'],
            self::SOURCE_CREDIBILITY => ['asc', 'desc'],
            self::REGION => ['asc', 'desc'],
            self::HEADLINE_LENGTH => ['asc', 'desc'],
        ];
    }
}
