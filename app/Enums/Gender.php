<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use Atannex\Filters\GetEnum;

/**
 * Gender Enum (string values for gender options)
 *
 * Represents multiple gender identity options.
 */
final class Gender extends Enum
{
    use GetEnum;

    public const MALE              = 'male';

    public const FEMALE            = 'female';

    public const NON_BINARY        = 'non_binary';

    public const TRANSGENDER       = 'transgender';

    public const OTHER             = 'other';

    public const PREFER_NOT_TO_SAY = 'prefer_not_to_say';

    /**
     * Boot method to define metadata for each gender option.
     */
    public static function boot(): void
    {
        self::setMetadata([
            self::MALE => [
                'label'       => 'Male',
                'description' => 'Male gender identity',
                'color'       => 'blue',
                'icon'        => 'heroicon-o-user',
            ],
            self::FEMALE => [
                'label'       => 'Female',
                'description' => 'Female gender identity',
                'color'       => 'pink',
                'icon'        => 'heroicon-o-user',
            ],
            self::NON_BINARY => [
                'label'       => 'Non-Binary',
                'description' => 'Non-binary or genderqueer identity',
                'color'       => 'purple',
                'icon'        => 'heroicon-o-sparkles',
            ],
            self::TRANSGENDER => [
                'label'       => 'Transgender',
                'description' => 'Transgender identity',
                'color'       => 'teal',
                'icon'        => 'heroicon-o-adjustments',
            ],
            self::OTHER => [
                'label'       => 'Other',
                'description' => 'Other gender identity not listed',
                'color'       => 'gray',
                'icon'        => 'heroicon-o-ellipsis-horizontal',
            ],
            self::PREFER_NOT_TO_SAY => [
                'label'       => 'Prefer Not to Say',
                'description' => 'Chose not to disclose gender identity',
                'color'       => 'neutral',
                'icon'        => 'heroicon-o-eye-slash',
            ],
        ]);
    }
}
