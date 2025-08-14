<?php

declare(strict_types=1);

namespace Atangageih\Services;

use Atangageih\Contracts\SocialShareInterface;

/**
 * Service class for handling social media sharing operations.
 */
class SocialShareService
{
    /**
     * Create a new instance of the social share service.
     *
     * @param SocialShareInterface $socialShare The social share repository.
     */
    public function __construct(
        public readonly SocialShareInterface $socialShare,
    ) {
    }

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
        return $this->socialShare->share($platform, $url, $text, $image, $utm);
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
        return $this->socialShare->getLabel($platform);
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
        return $this->socialShare->getIconClass($platform);
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
        return $this->socialShare->getColor($platform);
    }

    /**
     * Get all available platforms and their data.
     *
     * @return array Array of platform data.
     */
    public function getAllPlatforms(): array
    {
        return $this->socialShare->getAllPlatforms();
    }
}
