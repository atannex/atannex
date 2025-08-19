<?php

declare(strict_types=1);

namespace Ngangagah\Parameters\Traits;

use App\Models\Posts\Post;
use App\Models\User;

/**
 * Trait to handle social sharing functionality for posts.
 */
trait SocialShare
{
    /**
     * Builds social share data for a given post.
     *
     * @param Post $post The post to generate share data for
     * @param User|null $user The authenticated user, if available
     * @return array<int, array<string, string>> Array of social share data for each platform
     */
    private function buildSocialShareData(Post $post, ?User $user = null): array
    {
        $postUrl = $this->getPostUrl($post);

        return collect($this->socialShare->getAllPlatforms())
            ->map(function (array $data, string $platform) use ($post, $postUrl, $user): array {
                $shareUrl = $this->socialShare->share(
                    platform: $platform,
                    url: $postUrl,
                    shareable: $post,
                    user: $user,
                    text: $post->title ?? '',
                    image: $post->image ?? '',
                    utm: $this->getUtmParams($post->slug_path ?? '')
                );

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
     */
    private function getPostUrl(Post $post): string
    {
        return url($post->slug_path);
    }

    /**
     * Generates standard UTM parameters for post sharing.
     *
     * @param string $slugPath The post slug path
     * @return array<string, string> UTM parameters
     */
    private function getUtmParams(string $slugPath): array
    {
        return [
            'utm_source'   => 'website',
            'utm_medium'   => 'social',
            'utm_campaign' => 'post_share',
            'utm_content'  => $slugPath,
        ];
    }
}
