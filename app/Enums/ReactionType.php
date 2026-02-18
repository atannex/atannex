<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static LIKE()
 * @method static static DISLIKE()
 */
final class ReactionType extends Enum
{
    public const LIKE = 'like';

    public const DISLIKE = 'dislike';
}
