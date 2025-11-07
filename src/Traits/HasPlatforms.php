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

    /**
     * Return all social platform metadata from Icon enum.
     *
     * @return array<int, array{label:string,icon:string,color:string,platform:string}>
     */
    /**
     * Return only supported social platform icon metadata.
     *
     * @return array<string, array{label:string,icon:string,color:string}>
     */
    protected function getAllShareIcons(): array
    {
        return collect(self::SUPPORTED_PLATFORMS)
            ->mapWithKeys(fn(string $platform) => [
                $platform => Icon::getData($platform)
            ])
            ->all();
    }
}
