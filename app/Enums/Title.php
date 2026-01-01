<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Attributes\Description;

/**
 * Title Enum
 *
 * Represents various traditional ruler titles.
 */
final class Title extends Enum
{
    #[Description('HRM')]
    public const HRM = 'hrm';

    #[Description('NYIATEMEH')]
    public const NYIATEMEH = 'nyiatemeh';

    #[Description('HRH')]
    public const HRH = 'hrh';

    #[Description('HRH-MARFOW')]
    public const HRH_MARFOW = 'hrh-marfow';

    #[Description('MARFOW')]
    public const MARFOW = 'marfow';

    #[Description('NDI-NKEM')]
    public const NDI_NKEM = 'ndi-nkem';

    #[Description('NKEM')]
    public const NKEM = 'nkem';

    #[Description('MBE')]
    public const MBE = 'mbe';

    #[Description('MBE-MORFAW')]
    public const MBE_MORFAW = 'mbe-morfaw';

    #[Description('NWET')]
    public const NWET = 'nwet';

    #[Description('MBI')]
    public const MBI = 'mbi';

    #[Description('AFUNGONG')]
    public const AFUNGONG = 'afungong';
}
