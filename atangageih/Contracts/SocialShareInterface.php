<?php

namespace Atangageih\Contracts;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Enums\Social;

/**
 * Interface for handling social media sharing functionality and tracking.
 */
interface SocialShareInterface
{
    /**
     * Share a post to a specified social media platform and optionally track the share.
     *
     * @param Social $platform The social media platform enum instance.
     * @param string $url The URL to share.
     * @param Model|null $shareable The shareable entity (e.g., Post) for tracking, if applicable.
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
    ): string;

    /**
     * Get the display label for a platform.
     *
     * @param Social $platform The social media platform enum instance.
     * @return string The platform's display label.
     */
    public function getLabel(Social $platform): string;

    /**
     * Get the Font Awesome icon class for a platform.
     *
     * @param Social $platform The social media platform enum instance.
     * @return string The platform's icon class.
     */
    public function getIconClass(Social $platform): string;

    /**
     * Get the brand color for a platform.
     *
     * @param Social $platform The social media platform enum instance.
     * @return string The platform's brand color (hex code).
     */
    public function getColor(Social $platform): string;

    /**
     * Get all available platforms and their data.
     *
     * @return array<string, array<string, string>> Array of platform data.
     */
    public function getAllPlatforms(): array;
}
