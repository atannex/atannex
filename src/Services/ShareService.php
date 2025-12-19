<?php

declare(strict_types=1);

namespace Atannex\Services;

use App\Enums\Icon;
use App\Models\Interactions\Share;
use Atannex\Concerns\HasPlatforms;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Jorenvh\Share\ShareFacade;

class ShareService
{
    use HasPlatforms;

    /**
     * Build shareable URLs for all supported platforms.
     *
     * @param string $url
     * @param string $title
     *
     * @return array<string,string>
     */
    public function generate(string $url, string $title): array
    {
        $baseLinks = ShareFacade::page($url, $title)
            ->facebook()
            ->twitter()
            ->linkedin()
            ->whatsapp()
            ->telegram()
            ->getRawLinks();

        $links = [];

        foreach (self::SUPPORTED_PLATFORMS as $platform) {
            $links[$platform] = match ($platform) {

                Icon::FACEBOOK,
                Icon::TWITTER,
                Icon::LINKEDIN,
                Icon::WHATSAPP,
                Icon::TELEGRAM => $baseLinks[$platform],

                Icon::PINTEREST => 'https://pinterest.com/pin/create/button/?' . http_build_query([
                    'url'         => $url,
                    'description' => $title,
                ]),

                Icon::EMAIL => 'mailto:?subject=' . rawurlencode($title)
                    . '&body=' . rawurlencode($url),
            };
        }

        return $links;
    }

    /**
     * Record a share event.
     *
     * One call = one share.
     * No counters, no updates, no race conditions.
     */
    public function recordShare(Model $model, string $platform): Share
    {
        return Share::create([
            'shareable_id'   => $model->getKey(),
            'shareable_type' => $model::class,
            'visitor_id'     => app('visitor_id'),
            'user_id'        => Auth::id(),
            'platform'       => $platform,
            'shared_at'      => now(),
        ]);
    }
}
