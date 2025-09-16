<?php

declare(strict_types=1);

namespace Atannex\Services;

use Atannex\Contracts\ShareInterface;

/**
 * Service for sharing posts to social media platforms.
 */
class ShareService
{
    public function __construct(
        protected ShareInterface $interface
    ) {}

    /**
     * Generate a share URL for a specific platform and post.
     *
     * @param string $platform The social media platform (e.g., Icons::TWITTER)
     * @param string $postUrl The URL of the post to share
     * @param string|null $postTitle The title or description of the post (optional)
     * @return string The complete share URL
     * @throws \InvalidArgumentException If the post URL is invalid
     */
    public function shareToPlatform(string $platform, string $postUrl, ?string $postTitle = null): string
    {
        return $this->interface::getShareUrlForPost($platform, $postUrl, $postTitle);
    }

    /**
     * Get share URLs for all platforms for a given post.
     *
     * @param string $postUrl The URL of the post to share
     * @param string|null $postTitle The title or description of the post (optional)
     * @return array<string, array{label: string, url: string}>
     * @throws \InvalidArgumentException If the post URL is invalid
     */
    public function getAllShareUrls(string $postUrl, ?string $postTitle = null): array
    {

        return $this->interface::getAllShareUrls($postUrl, $postTitle);
    }
}
