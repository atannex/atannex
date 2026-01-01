<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Attributes\Description;

/**
 * Classification Enum
 *
 * Represents hierarchical levels of traditional rulers
 * based on official recognition and authority.
 */
final class Classification extends Enum
{
    #[Description('First-Class Chief')]
    public const FIRST_CLASS = 'first_class';

    #[Description('Second-Class Chief')]
    public const SECOND_CLASS = 'second_class';

    #[Description('Third-Class Chief')]
    public const THIRD_CLASS = 'third_class';

    #[Description('Fourth-Class Chief')]
    public const FOURTH_CLASS = 'fourth_class';

    #[Description('Unclassified')]
    public const UNCLASSIFIED = 'unclassified';
}
