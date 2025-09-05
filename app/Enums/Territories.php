<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use Atannex\Filters\GetEnum;

final class Territories extends Enum
{
    use GetEnum;

    public const FONDOM       = 'fondom';

    public const CHIEFDOM     = 'chiefdom';

    public const VILLAGE      = 'village';

    public const QUARTER      = 'quarter';

    public const DIVISION     = 'division';

    public const SUB_DIVISION = 'sub_division';

    public static function boot(): void
    {
        static::setMetadata([
            self::FONDOM => [
                'label' => 'Fondom',
                'description' => 'Traditional territory led by a Fon',
                'color' => 'primary',
                'icon' => 'heroicon-o-globe-alt',
            ],
            self::CHIEFDOM => [
                'label' => 'Chiefdom',
                'description' => 'Territory governed by a Chief',
                'color' => 'success',
                'icon' => 'heroicon-o-shield-check',
            ],
            self::VILLAGE => [
                'label' => 'Village',
                'description' => 'Small rural settlement',
                'color' => 'info',
                'icon' => 'heroicon-o-home',
            ],
            self::QUARTER => [
                'label' => 'Quarter',
                'description' => 'Subdivision of a village or town',
                'color' => 'warning',
                'icon' => 'heroicon-o-office-building',
            ],
            self::DIVISION => [
                'label' => 'Division',
                'description' => 'Administrative division within a region',
                'color' => 'danger',
                'icon' => 'heroicon-o-puzzle',
            ],
            self::SUB_DIVISION => [
                'label' => 'Sub-Division',
                'description' => 'Smaller administrative section of a division',
                'color' => 'secondary',
                'icon' => 'heroicon-o-collection',
            ],
        ]);
    }
}
