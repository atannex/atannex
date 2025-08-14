<?php

declare(strict_types=1);

namespace Atangageih\Repositories;

use App\Enums\Social;
use Atangageih\Contracts\SocialShareInterface;

/**
 * Repository for handling social media sharing functionality.
 */
class SocialShareRepository implements SocialShareInterface
{
    /**
     * Share a post to a specified social media platform.
     *
     * @param string $platform The social media platform (e.g., Social::FACEBOOK).
     * @param string $url The URL to share.
     * @param string|null $text Optional text to include in the share.
     * @param string|null $image Optional image URL for platforms that support images.
     * @param array $utm Optional UTM parameters for tracking.
     * @return string The complete share URL for the platform.
     * @throws \InvalidArgumentException If the platform is invalid.
     */
    public function share(
        string $platform,
        string $url,
        ?string $text = null,
        ?string $image = null,
        array $utm = []
    ): string {
        $platformData = Social::getPlatformData($platform);

        if (!empty($utm)) {
            $url .= (parse_url($url, PHP_URL_QUERY) ? '&' : '?') . http_build_query($utm);
        }

        $shareUrl = $platformData['share_url'] . urlencode($url);

        if ($platform === Social::WHATSAPP && $text !== null) {
            $shareUrl = $platformData['share_url'] . urlencode($text . ' ' . $url);
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

        return $shareUrl;
    }

    /**
     * Get the display label for a platform.
     *
     * @param string $platform The social media platform.
     * @return string The platform's display label.
     * @throws \InvalidArgumentException If the platform is invalid.
     */
    public function getLabel(string $platform): string
    {
        return Social::getLabel($platform);
    }

    /**
     * Get the Font Awesome icon class for a platform.
     *
     * @param string $platform The social media platform.
     * @return string The platform's icon class.
     * @throws \InvalidArgumentException If the platform is invalid.
     */
    public function getIconClass(string $platform): string
    {
        return Social::getIconClass($platform);
    }

    /**
     * Get the brand color for a platform.
     *
     * @param string $platform The social media platform.
     * @return string The platform's brand color (hex code).
     * @throws \InvalidArgumentException If the platform is invalid.
     */
    public function getColor(string $platform): string
    {
        return Social::getColor($platform);
    }

    /**
     * Get all available platforms and their data.
     *
     * @return array Array of platform data.
     */
    public function getAllPlatforms(): array
    {
        return Social::getAllPlatforms();
    }
}
