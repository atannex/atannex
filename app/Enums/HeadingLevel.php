<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Attributes\Description;

final class HeadingLevel extends Enum
{
    #[Description('H1 – Page Title')]
    public const H1 = 'h1';

    #[Description('H2 – Section Title')]
    public const H2 = 'h2';

    #[Description('H3 – Subsection')]
    public const H3 = 'h3';

    #[Description('H4 – Minor Heading')]
    public const H4 = 'h4';

    #[Description('H5 – Small Heading')]
    public const H5 = 'h5';

    #[Description('H6 – Caption / Label')]
    public const H6 = 'h6';
}
