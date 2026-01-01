<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Attributes\Description;

/**
 * Territories Enum
 *
 * Represents various traditional and administrative territory levels.
 */
final class Territories extends Enum
{
    #[Description('Fondom')]
    public const FONDOM = 'fondom';

    #[Description('Chiefdom')]
    public const CHIEFDOM = 'chiefdom';

    #[Description('Village')]
    public const VILLAGE = 'village';

    #[Description('Quarter')]
    public const QUARTER = 'quarter';

    #[Description('Division')]
    public const DIVISION = 'division';

    #[Description('Sub-Division')]
    public const SUB_DIVISION = 'sub_division';
}
