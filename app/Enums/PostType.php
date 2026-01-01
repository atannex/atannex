<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Attributes\Description;

/**
 * PostType Enum
 *
 * Represents different types of posts in the system.
 */
final class PostType extends Enum
{
    #[Description('Article')]
    public const ARTICLE = 'article';

    #[Description('Video')]
    public const VIDEO = 'video';

    #[Description('Audio')]
    public const AUDIO = 'audio';
}
