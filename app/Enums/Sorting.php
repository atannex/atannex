<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static PublishedAt()
 * @method static static CreatedAt()
 * @method static static Views()
 * @method static static CommentsCount()
 * @method static static LikesCount()
 * @method static static SharesCount()
 */
final class Sorting extends Enum
{
    const PUBLISHED_AT   = 'published_at';

    const CREATED_AT     = 'created_at';

    const VIEWS          = 'views';

    const COMMENTS_COUNT = 'comments_count';

    const LIKES_COUNT    = 'likes_count';

    const SHARES_COUNT   = 'shares_count';

    /**
     * Return options formatted for Filament Select components.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::PUBLISHED_AT   => 'Published Date',
            self::CREATED_AT     => 'Creation Date',
            self::VIEWS          => 'Views',
            self::COMMENTS_COUNT => 'Comments Count',
            self::LIKES_COUNT    => 'Likes Count',
            self::SHARES_COUNT   => 'Shares Count',
        ];
    }
}
