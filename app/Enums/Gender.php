<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use Atannex\Filters\GetEnum;

/**
 * Gender Enum (string values for male and female)
 *
 * Represents gender options as strings.
 */
final class Gender extends Enum
{
    use GetEnum;

    public const MALE = 'male';

    public const FEMALE = 'female';

    /**
     * Boot method to define metadata for each gender option.
     */
    public static function boot(): void
    {
        static::setMetadata([
            self::MALE => [
                'label' => 'Male',
                'description' => 'Male gender',
                'color' => 'primary',
                'icon' => 'heroicon-o-user',
            ],
            self::FEMALE => [
                'label' => 'Female',
                'description' => 'Female gender',
                'color' => 'pink',
                'icon' => 'heroicon-o-user',
            ],
        ]);
    }
}
