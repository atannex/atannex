<?php

namespace Atannex\Concerns;

use App\Enums\Icon;

trait HasPlatforms
{
    public const SUPPORTED_PLATFORMS = [
        Icon::FACEBOOK,
        Icon::TWITTER,
        Icon::WHATSAPP,
        Icon::TELEGRAM,
    ];

    protected function getAllShareIcons(): array
    {
        return collect(self::SUPPORTED_PLATFORMS)
            ->mapWithKeys(fn (string $platform) => [
                $platform => Icon::getData($platform),
            ])
            ->all();
    }
}
