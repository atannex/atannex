<?php

declare(strict_types=1);

namespace App\Enums\Traits;

use App\Enums\Icons;

trait HasSocialSharing
{
    /**
     * Generate a share URL for a specific platform and post.
     *
     * @param string      $platform   The social media platform (Icons enum value)
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

        // Retrieve the enum instance for the given platform value
        $enumInstance = Icons::fromValue($platform);

        // Retrieve the base share URL from the Icons enum metadata
        $baseUrl = $enumInstance->getMetadata('share_url');
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
                if ($postTitle) {
                    $params['text'] = $postTitle;
                }
                if ($postUrl) {
                    $params['url'] = $postUrl;
                }
                // Twitter/X card will pick up OG tags from the post URL
                return $baseUrl . '?' . http_build_query($params);

            case Icons::PINTEREST:
                $params = ['url' => $postUrl];
                if ($postImage) {
                    $params['media'] = $postImage;
                }
                if ($postDesc) {
                    $params['description'] = $postDesc;
                } elseif ($postTitle) {
                    $params['description'] = $postTitle;
                }
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
     * @return array<string, array{label: string, url: string, icon: string, color: string, description: string}>
     */
    public static function getAllShareUrls(
        string $postUrl,
        ?string $postTitle = null,
        ?string $postImage = null,
        ?string $postDesc = null
    ): array {

        $result = [];
        foreach (Icons::getOptions() as $platform => $data) {
            $result[$platform] = [
                'label' => $data['label'],
                'url' => self::getShareUrlForPost($platform, $postUrl, $postTitle, $postImage, $postDesc),
                'icon' => $data['icon'],
                'color' => $data['color'],
            ];
        }
        return $result;
    }
}
