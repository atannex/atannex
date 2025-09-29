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
     * @param array $platforms List of platforms to generate links for.
     * @param string $linkedinSummary Optional summary text for LinkedIn shares.
     * @return array<string, string> An associative array of platform names and their share URLs.
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
            Icon::REDDIT
        ],
        string $linkedinSummary = ''
    ): array {
        $share = Share::page($url, $title);

        foreach ($platforms as $platform) {
            $method = strtolower($platform);
            if (method_exists($share, $method)) {
                $method === 'linkedin'
                    ? $share->linkedin($linkedinSummary)
                    : $share->$method();
            }
        }

        return $share->getRawLinks();
    }
}
