<?php

declare(strict_types=1);

namespace App\Enums\Traits;

use App\Enums\Icons;

trait HasSocialSharing
{
    /**
     * Generate a share URL for a specific platform and post.
     *
     * @param string      $platform   The social media platform
     * @param string      $postUrl    The URL of the post to share
     * @param string|null $postTitle  The title of the post
     * @param string|null $postImage  URL of the post image
     * @param string|null $postDesc   Description of the post
     *
     * @return string The complete share URL
     */
    public static function getShareUrlForPost(
        string $platform,
        string $postUrl,
        ?string $postTitle = null,
        ?string $postImage = null,
        ?string $postDesc = null
    ): string {
        $baseUrl = Icons::getShareUrl($platform);
        $encodedUrl = rawurlencode($postUrl);

        switch ($platform) {
            case Icons::WHATSAPP:
                $text = $postTitle ? rawurlencode($postTitle . ' ' . $postUrl) : $encodedUrl;
                return $baseUrl . $text;

            case Icons::TELEGRAM:
                $text = $postTitle ? '&text=' . rawurlencode($postTitle) : '';
                return $baseUrl . $encodedUrl . $text;

            case Icons::TWITTER:
                $params = [];
                if ($postTitle) $params['text'] = $postTitle;
                if ($postUrl) $params['url'] = $postUrl;
                // Twitter card will pick up OG tags from the post URL
                return $baseUrl . '?' . http_build_query($params);

            case Icons::PINTEREST:
                $params = ['url' => $postUrl];
                if ($postImage) $params['media'] = $postImage;
                if ($postTitle) $params['description'] = $postTitle;
                return $baseUrl . '?' . http_build_query($params);

            case Icons::LINKEDIN:
            case Icons::FACEBOOK:
            case Icons::REDDIT:
                // These platforms rely on OG tags in the page
                return $baseUrl . $encodedUrl;

            default:
                return $baseUrl . $encodedUrl;
        }
    }

    /**
     * Get share URLs for all platforms for a given post.
     *
     * @param string      $postUrl
     * @param string|null $postTitle
     * @param string|null $postImage
     * @param string|null $postDesc
     *
     * @return array<string, array{label: string, url: string, icon: string, color: string}>
     */
    public static function getAllShareUrls(
        string $postUrl,
        ?string $postTitle = null,
        ?string $postImage = null,
        ?string $postDesc = null
    ): array {
        $result = [];
        foreach (Icons::all() as $platform => $data) {
            $result[$platform] = [
                'label' => $data['label'],
                'url'   => self::getShareUrlForPost($platform, $postUrl, $postTitle, $postImage, $postDesc),
                'icon'  => $data['icon'],
                'color' => $data['color'],
            ];
        }
        return $result;
    }
}
