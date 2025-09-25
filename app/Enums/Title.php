<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use Atannex\Filters\GetEnum;

/**
 * Title Enum (string values for traditional ruler titles)
 *
 * Represents various traditional ruler titles with metadata.
 */
final class Title extends Enum
{
    use GetEnum;

    public const HRM         = 'hrm';
    public const NYIATEMEH   = 'nyiatemeh';
    public const HRH         = 'hrh';
    public const HRH_MARFOW  = 'hrh-marfow';
    public const MARFOW      = 'marfow';
    public const NDI_NKEM    = 'ndi-nkem';
    public const NKEM        = 'nkem';
    public const MBE         = 'mbe';
    public const MBE_MORFAW  = 'mbe-morfaw';
    public const NWET        = 'nwet';
    public const MBI         = 'mbi';
    public const AFUNGONG    = 'afungong';

    /**
     * Boot method to define metadata for each title.
     */
    public static function boot(): void
    {
        self::setMetadata([
            self::HRM => [
                'label'       => 'HRM',
                'description' => 'His/Her Royal Majesty',
                'color'       => 'primary',
                'icon'        => 'heroicon-o-crown',
            ],
            self::NYIATEMEH => [
                'label'       => 'NYIATEMEH',
                'description' => 'Traditional title NYIATEMEH',
                'color'       => 'success',
                'icon'        => 'heroicon-o-shield-check',
            ],
            self::HRH => [
                'label'       => 'HRH',
                'description' => 'His/Her Royal Highness',
                'color'       => 'info',
                'icon'        => 'heroicon-o-certificate',
            ],
            self::HRH_MARFOW => [
                'label'       => 'HRH-MARFOW',
                'description' => 'HRH MARFOW traditional ruler',
                'color'       => 'warning',
                'icon'        => 'heroicon-o-badge-check',
            ],
            self::MARFOW => [
                'label'       => 'MARFOW',
                'description' => 'MARFOW traditional ruler',
                'color'       => 'secondary',
                'icon'        => 'heroicon-o-user',
            ],
            self::NDI_NKEM => [
                'label'       => 'NDI-NKEM',
                'description' => 'NDI-NKEM title holder',
                'color'       => 'purple',
                'icon'        => 'heroicon-o-star',
            ],
            self::NKEM => [
                'label'       => 'NKEM',
                'description' => 'NKEM title holder',
                'color'       => 'teal',
                'icon'        => 'heroicon-o-emoji-happy',
            ],
            self::MBE => [
                'label'       => 'MBE',
                'description' => 'MBE title holder',
                'color'       => 'info',
                'icon'        => 'heroicon-o-medal',
            ],
            self::MBE_MORFAW => [
                'label'       => 'MBE-MORFAW',
                'description' => 'MBE MORFAW title holder',
                'color'       => 'warning',
                'icon'        => 'heroicon-o-badge-check',
            ],
            self::NWET => [
                'label'       => 'NWET',
                'description' => 'NWET title holder',
                'color'       => 'success',
                'icon'        => 'heroicon-o-user-group',
            ],
            self::MBI => [
                'label'       => 'MBI',
                'description' => 'MBI title holder',
                'color'       => 'primary',
                'icon'        => 'heroicon-o-star',
            ],
            self::AFUNGONG => [
                'label'       => 'AFUNGONG',
                'description' => 'AFUNGONG title holder',
                'color'       => 'secondary',
                'icon'        => 'heroicon-o-cog',
            ],
        ]);
    }
}
