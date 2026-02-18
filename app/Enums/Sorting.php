<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

/**
 * Sorting Enum
 *
 * Defines sorting options tailored for a news or content website.
 */
final class Sorting extends Enum
{
    #[Description('Published Date')]
    public const PUBLISHED_AT = 'published_at';

    #[Description('Creation Date')]
    public const CREATED_AT = 'created_at';

    #[Description('Last Updated')]
    public const UPDATED_AT = 'updated_at';

    #[Description('Comments Count')]
    public const COMMENTS = 'comments';

    #[Description('Category')]
    public const CATEGORY = 'category';

    #[Description('Region')]
    public const REGION = 'region';

    /**
     * Provide options for Filament Select by mapping each sorting value to its description.
     *
     * @return array<string, string> Associative array where keys are sorting values and values are their human-readable labels.
     */
    public static function options(): array
    {
        return [
            self::PUBLISHED_AT => self::getDescription(self::PUBLISHED_AT),
            self::CREATED_AT => self::getDescription(self::CREATED_AT),
            self::UPDATED_AT => self::getDescription(self::UPDATED_AT),
            self::COMMENTS => self::getDescription(self::COMMENTS),
            self::CATEGORY => self::getDescription(self::CATEGORY),
            self::REGION => self::getDescription(self::REGION),
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
