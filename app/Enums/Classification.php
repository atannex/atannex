<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use Atannex\Filters\GetEnum;

/**
 * Classification Enum (string values for traditional ruler levels)
 *
 * Represents multiple hierarchical levels of traditional rulers.
 */
final class Classification extends Enum
{
    use GetEnum;

    public const FIRST_CLASS   = 'first_class';
    public const SECOND_CLASS  = 'second_class';
    public const THIRD_CLASS   = 'third_class';
    public const FOURTH_CLASS  = 'fourth_class';
    public const UNCLASSIFIED  = 'unclassified';

    /**
     * Boot method to define metadata for each classification level.
     */
    public static function boot(): void
    {
        self::setMetadata([
            self::FIRST_CLASS => [
                'label'       => 'First-Class Chief',
                'description' => 'Highest level traditional ruler with national or regional recognition',
                'color'       => 'primary',
                'icon'        => 'heroicon-o-crown',
            ],
            self::SECOND_CLASS => [
                'label'       => 'Second-Class Chief',
                'description' => 'Regional or district-level traditional ruler',
                'color'       => 'success',
                'icon'        => 'heroicon-o-shield-check',
            ],
            self::THIRD_CLASS => [
                'label'       => 'Third-Class Chief',
                'description' => 'Local-level traditional ruler, presiding over towns or villages',
                'color'       => 'warning',
                'icon'        => 'heroicon-o-user-group',
            ],
            self::FOURTH_CLASS => [
                'label'       => 'Fourth-Class Chief',
                'description' => 'Minor ruler, often heads of clans or quarters',
                'color'       => 'info',
                'icon'        => 'heroicon-o-badge-check',
            ],
            self::UNCLASSIFIED => [
                'label'       => 'Unclassified',
                'description' => 'Ruler without formal government recognition or ceremonial role',
                'color'       => 'secondary',
                'icon'        => 'heroicon-o-question-mark-circle',
            ],
        ]);
    }
}
