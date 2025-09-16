<?php

declare(strict_types=1);

namespace Atannex\Contracts;

/**
 * Interface for sharing posts to social media platforms.
 */
interface ShareInterface
{
    /**
     * Generate a share URL for a specific platform and post.
     *
     * @param string $platform The social media platform (e.g., Icons::TWITTER)
     * @param string $postUrl The URL of the post to share
     * @param string|null $postTitle The title or description of the post (optional)
     * @return string The complete share URL
     */
    public static function getShareUrlForPost(string $platform, string $postUrl, ?string $postTitle = null): string;

    /**
     * Get share URLs for all platforms for a given post.
     *
     * @param string $postUrl The URL of the post to share
     * @param string|null $postTitle The title or description of the post (optional)
     * @return array<string, array{label: string, url: string}>
     */
    public static function getAllShareUrls(string $postUrl, ?string $postTitle = null): array;
}
