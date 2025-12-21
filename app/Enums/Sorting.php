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

    const COMMENTS = 'comments';

    const CATEGORY = 'category';

    const REGION = 'region';

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
            self::CATEGORY => 'Category',
            self::REGION => 'Region',
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
            self::COMMENTS => ['asc', 'desc'],
            self::CATEGORY => ['asc', 'desc'],
            self::REGION => ['asc', 'desc'],
        ];
    }
}
