<?php

declare(strict_types=1);

namespace Atannex\Services;

use App\Enums\Icon;
use Jorenvh\Share\ShareFacade;

class ShareService
{
    public const SUPPORTED_PLATFORMS = [
        Icon::FACEBOOK,
        Icon::TWITTER,
        Icon::WHATSAPP,
        Icon::TELEGRAM,
    ];

    /**
     * Build shareable URLs for all supported platforms.
     *
     * @return array<string,string>
     */
    public function generate(string $url, string $title): array
    {
        $baseLinks = ShareFacade::page($url, $title)
            ->facebook()
            ->twitter()
            ->whatsapp()
            ->telegram()
            ->getRawLinks();

        $links = [];

        foreach (self::SUPPORTED_PLATFORMS as $platform) {
            $links[$platform] = match ($platform) {
                Icon::FACEBOOK,
                Icon::TWITTER,
                Icon::WHATSAPP,
                Icon::TELEGRAM => $baseLinks[$platform],
            };
        }

        return $links;
    }
}
