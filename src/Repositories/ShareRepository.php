<?php

declare(strict_types=1);

namespace Atannex\Repositories;

use App\Enums\Icon;
use Atannex\Contracts\ShareInterface;
use Jorenvh\Share\ShareFacade as Share;

/**
 * Repository for generating social media share links.
 */
class ShareRepository implements ShareInterface
{
    /**
     * Generates raw social media share links without HTML markup.
     *
     * @param string $url The URL to be shared.
     * @param string $title The title or text for the share.
     * @param array<string> $platforms List of platforms to generate links for.
     * @param string $linkedinSummary Optional summary text for LinkedIn shares.
     * @return array<string, string> Associative array of platform names and their share URLs.
     */
    public function getRawShareLinks(
        string $url,
        string $title,
        array $platforms = [
            Icon::FACEBOOK,
            Icon::TWITTER,
            Icon::LINKEDIN,
            Icon::WHATSAPP,
            Icon::TELEGRAM,
            Icon::REDDIT,
        ],
        string $linkedinSummary = ''
    ): array {
        $share = $this->initializeShareInstance($url, $title);
        $this->applyPlatforms($share, $platforms, $linkedinSummary);

        return $this->extractRawLinks($share);
    }

    /**
     * Initialize the Share builder instance with URL and title.
     */
    protected function initializeShareInstance(string $url, string $title): object
    {
        return Share::page($url, $title);
    }

    /**
     * Apply all requested social platforms to the Share instance.
     */
    protected function applyPlatforms(object $share, array $platforms, string $linkedinSummary): void
    {
        foreach ($platforms as $platform) {
            $method = strtolower($platform);

            if ($method === 'linkedin') {
                $share->linkedin($linkedinSummary);
                continue;
            }

            if (method_exists($share, $method)) {
                $share->$method();
            }
        }
    }

    /**
     * Extract the final raw share URLs.
     *
     * @return array<string, string>
     */
    protected function extractRawLinks(object $share): array
    {
        return $share->getRawLinks();
    }
}
