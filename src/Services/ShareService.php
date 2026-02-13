<?php

declare(strict_types=1);

namespace Atannex\Services;

use App\Enums\Icon;
use Illuminate\Support\Str;
use App\Models\Comments\Share;
use Jorenvh\Share\ShareFacade;
use Atannex\Concerns\HasPlatforms;
use Illuminate\Support\Facades\Request;

class ShareService
{
    use HasPlatforms;

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
                Icon::FACEBOOK  => $baseLinks[Icon::FACEBOOK],
                Icon::TWITTER   => $baseLinks[Icon::TWITTER],
                Icon::WHATSAPP  => $baseLinks[Icon::WHATSAPP],
                Icon::TELEGRAM  => $baseLinks[Icon::TELEGRAM],
            };
        }

        return $links;
    }

    /**
     * Generate a single platform URL.
     */
    public function generateFor(string $platform, string $url, string $title): string
    {
        $links = $this->generate($url, $title);
        return $links[$platform];
    }
}
