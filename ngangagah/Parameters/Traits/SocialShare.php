<?php

declare(strict_types=1);

namespace Ngangagah\Parameters\Traits;

use App\Models\Posts\Post;
use InvalidArgumentException;
use RuntimeException;

/**
 * Trait to handle social sharing functionality for posts.
 */
trait SocialShare
{
    /**
     * Builds social share data for a given post.
     *
     * @param Post $post The post to generate share data for
     * @return array<int, array<string, string>> Array of social share data for each platform
     * @throws InvalidArgumentException If the post or social share configuration is invalid
     * @throws RuntimeException If share URL generation fails
     */
    private function buildSocialShareData(Post $post): array
    {
        if (!$post instanceof Post) {
            throw new InvalidArgumentException('Invalid post object provided');
        }

        $postUrl = $this->getPostUrl($post);

        return collect($this->socialShare->getAllPlatforms())
            ->map(function (array $data, string $platform) use ($post, $postUrl): array {
                $shareUrl = $this->socialShare->share(
                    platform: $platform,
                    url: $postUrl,
                    text: $post->title ?? '',
                    image: $post->image ?? '',
                    utm: $this->getUtmParams($post->slug_path ?? '')
                );

                if (empty($shareUrl)) {
                    throw new RuntimeException("Failed to generate share URL for platform: {$platform}");
                }

                return [
                    'platform'   => $platform,
                    'label'      => $data['label'] ?? $platform,
                    'icon'       => $data['icon'] ?? '',
                    'color'      => $data['color'] ?? '',
                    'share_url'  => $shareUrl,
                ];
            })
            ->values()
            ->toArray();
    }

    /**
     * Generates the full URL for a given post.
     *
     * @param Post $post The post to generate URL for
     * @return string The complete URL to the post
     * @throws InvalidArgumentException If the post slug path is invalid
     */
    private function getPostUrl(Post $post): string
    {
        if (empty($post->slug_path)) {
            throw new InvalidArgumentException('Post slug path is empty');
        }

        $url = url($post->slug_path);

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException('Generated post URL is invalid');
        }

        return $url;
    }

    /**
     * Generates standard UTM parameters for post sharing.
     *
     * @param string $slugPath The post slug path
     * @return array<string, string> UTM parameters
     * @throws InvalidArgumentException If the slug path is empty
     */
    private function getUtmParams(string $slugPath): array
    {
        if (empty($slugPath)) {
            throw new InvalidArgumentException('Slug path cannot be empty');
        }

        return [
            'utm_source'   => 'website',
            'utm_medium'   => 'social',
            'utm_campaign' => 'post_share',
            'utm_content'  => $slugPath,
        ];
    }
}
