<?php

namespace Atannex\Traits;

use App\Enums\Icon;

trait HasPlatforms
{
    /**
     * Social platforms supported by Laravel Share package.
     */
    public const SUPPORTED_PLATFORMS = [
        Icon::FACEBOOK,
        Icon::TWITTER,
        Icon::LINKEDIN,
        Icon::WHATSAPP,
        Icon::TELEGRAM,
        Icon::PINTEREST,
        Icon::EMAIL,
    ];
}
