<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Attributes\Description;

final class TailwindColor extends Enum
{
    // Base colors
    #[Description('Slate')]
    const SLATE = 'slate';

    #[Description('Gray')]
    const GRAY = 'gray';

    #[Description('Zinc')]
    const ZINC = 'zinc';

    #[Description('Neutral')]
    const NEUTRAL = 'neutral';

    #[Description('Stone')]
    const STONE = 'stone';

    // Primary colors
    #[Description('Red')]
    const RED = 'red';

    #[Description('Orange')]
    const ORANGE = 'orange';

    #[Description('Amber')]
    const AMBER = 'amber';

    #[Description('Yellow')]
    const YELLOW = 'yellow';

    #[Description('Lime')]
    const LIME = 'lime';

    #[Description('Green')]
    const GREEN = 'green';

    #[Description('Emerald')]
    const EMERALD = 'emerald';

    #[Description('Teal')]
    const TEAL = 'teal';

    #[Description('Cyan')]
    const CYAN = 'cyan';

    #[Description('Sky')]
    const SKY = 'sky';

    #[Description('Blue')]
    const BLUE = 'blue';

    #[Description('Indigo')]
    const INDIGO = 'indigo';

    #[Description('Violet')]
    const VIOLET = 'violet';

    #[Description('Purple')]
    const PURPLE = 'purple';

    #[Description('Fuchsia')]
    const FUCHSIA = 'fuchsia';

    #[Description('Pink')]
    const PINK = 'pink';

    #[Description('Rose')]
    const ROSE = 'rose';

    // Utility colors
    #[Description('Black')]
    const BLACK = 'black';

    #[Description('White')]
    const WHITE = 'white';

    #[Description('Transparent')]
    const TRANSPARENT = 'transparent';

    #[Description('Current')]
    const CURRENT = 'current';
}
