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

    /**
     * Build shareable URLs for all supported platforms.
     *
     * This method:
     *  - Uses the Jorenvh\Share package to generate default links for major platforms.
     *  - Extends support to custom platforms (Pinterest, Email) with manually crafted URLs.
     *  - Guarantees a complete map of platform → share URL.
     *
     * @param string $url   Fully qualified URL to be shared.
     * @param string $title Display title used by some platforms.
     *
     * @return array<string,string> Associative array of platform keys to share URLs.
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
     * Record a share event for analytics and engagement tracking.
     *
     * Logic:
     *  - One record per user/platform/model is maintained.
     *  - If a record exists, its share_count is incremented.
     *  - Otherwise, a new record is stored with an initial count.
     *
     * @param object $model    Any shareable model (Post, Video, etc.).
     * @param string $platform Platform key representing where the share occurred.
     *
     * @return Share The updated or newly created share record.
     */
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
                'shareable_id'   => $model->id,
                'shareable_type' => get_class($model),
                'user_id'        => $userId,
                'platform'       => $platform,
                'share_count'    => 1,
                'shared_at'      => now(),
            ]);
        }

        return $share;
    }
}
