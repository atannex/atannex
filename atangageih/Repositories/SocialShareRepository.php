<?php

declare(strict_types=1);

namespace Atangageih\Repositories;

use App\Models\User;
use App\Enums\Social;
use App\Models\Interactions\Share;
use Illuminate\Database\Eloquent\Model;
use Atangageih\Contracts\SocialShareInterface;

/**
 * Repository for handling social media sharing functionality and tracking.
 */
class SocialShareRepository implements SocialShareInterface
{
    /**
     * Share a post to a specified social media platform and track the share.
     *
     * @param Social $platform The social media platform enum instance.
     * @param string $url The URL to share.
     * @param Model|null $shareable The shareable entity (e.g., Post).
     * @param User|null $user The user performing the share, if authenticated.
     * @param string|null $text Optional text to include in the share.
     * @param string|null $image Optional image URL for platforms that support images.
     * @param array<string, string> $utm Optional UTM parameters for tracking.
     * @return string The complete share URL for the platform.
     */
    public function share(
        Social $platform,
        string $url,
        ?Model $shareable = null,
        ?User $user = null,
        ?string $text = null,
        ?string $image = null,
        array $utm = []
    ): string {
        $shareUrl = $platform->shareUrl() . urlencode($url);

        if (!empty($utm)) {
            $shareUrl .= (parse_url($url, PHP_URL_QUERY) ? '&' : '?') . http_build_query($utm);
        }

        // Special handling for platform-specific parameters
        if ($platform === Social::WHATSAPP && $text !== null) {
            $shareUrl = $platform->shareUrl() . urlencode($text . ' ' . $url);
        } elseif ($platform === Social::PINTEREST && $image !== null) {
            $shareUrl .= '&media=' . urlencode($image);
            if ($text !== null) {
                $shareUrl .= '&description=' . urlencode($text);
            }
        } elseif ($text !== null) {
            $textParam = match ($platform) {
                Social::X => 'text',
                Social::LINKEDIN => 'title',
                Social::REDDIT => 'title',
                Social::TELEGRAM => 'text',
                Social::TUMBLR => 'caption',
                Social::FACEBOOK => 'quote',
                default => null,
            };
            if ($textParam) {
                $shareUrl .= '&' . $textParam . '=' . urlencode($text);
            }
        }

        if ($shareable !== null) {
            $this->trackShare($platform, $shareable, $user);
        }

        return $shareUrl;
    }

    /**
     * Track a share action in the database.
     *
     * @param Social $platform The social media platform enum instance.
     * @param Model $shareable The shareable entity (e.g., Post).
     * @param User|null $user The user performing the share, if authenticated.
     */
    protected function trackShare(Social $platform, Model $shareable, ?User $user): void
    {
        $share = Share::where([
            'shareable_id' => $shareable->getKey(),
            'shareable_type' => get_class($shareable),
            'platform' => $platform->value,
            'user_id' => $user?->getKey(),
        ])->withTrashed()->first();

        if ($share) {
            $share->increment('share_count');
            if ($share->trashed()) {
                $share->restore();
            }
            $share->update(['shared_at' => now()]);
        } else {
            Share::create([
                'shareable_id' => $shareable->getKey(),
                'shareable_type' => get_class($shareable),
                'user_id' => $user?->getKey(),
                'platform' => $platform->value,
                'share_count' => 1,
                'shared_at' => now(),
            ]);
        }
    }

    public function getLabel(Social $platform): string
    {
        return $platform->label();
    }

    public function getIconClass(Social $platform): string
    {
        return $platform->icon();
    }

    public function getColor(Social $platform): string
    {
        return $platform->color();
    }

    public function getAllPlatforms(): array
    {
        return Social::getAllPlatforms();
    }
}
