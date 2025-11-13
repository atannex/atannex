<?php

declare(strict_types=1);

namespace Atannex\Services;

use App\Enums\Icon;
use App\Models\Interactions\Share;
use Atannex\Concerns\HasPlatforms;
use Illuminate\Support\Facades\Auth;
use Jorenvh\Share\ShareFacade;

class ShareService
{
    use HasPlatforms;

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

                Icon::PINTEREST => 'https://pinterest.com/pin/create/button/?'.http_build_query([
                    'url' => $url,
                    'description' => $title,
                ]),

                Icon::EMAIL => 'mailto:?subject='.rawurlencode($title)
                    .'&body='.rawurlencode($url),
            };
        }

        return $links;
    }

    public function recordShare(object $model, string $platform): Share
    {
        $userId = Auth::id();

        $share = Share::query()
            ->where('shareable_id', $model->id)
            ->where('shareable_type', get_class($model))
            ->where('platform', $platform)
            ->where('user_id', $userId)
            ->first();

        if ($share) {
            $share->increment('share_count');
        } else {
            $share = Share::create([
                'shareable_id' => $model->id,
                'shareable_type' => get_class($model),
                'user_id' => $userId,
                'platform' => $platform,
                'share_count' => 1,
                'shared_at' => now(),
            ]);
        }

        return $share;
    }
}
