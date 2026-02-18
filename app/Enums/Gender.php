<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

/**
 * Gender Enum
 *
 * Represents multiple gender identity options.
 */
final class Gender extends Enum
{
    #[Description('Male')]
    public const MALE = 'male';

    #[Description('Female')]
    public const FEMALE = 'female';

    #[Description('Non-Binary')]
    public const NON_BINARY = 'non_binary';

    #[Description('Transgender')]
    public const TRANSGENDER = 'transgender';

    #[Description('Other')]
    public const OTHER = 'other';

    #[Description('Prefer Not to Say')]
    public const PREFER_NOT_TO_SAY = 'prefer_not_to_say';
}
