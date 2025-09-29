<?php

declare(strict_types=1);

namespace Atannex\Contracts;

/**
 * Interface for generating social media share links.
 */
interface ShareInterface
{
    /**
     * Generates raw social media share links without HTML markup.
     *
     * @param string $url The URL to be shared.
     * @param string $title The title or text for the share.
     * @param array $platforms List of platforms to generate links for (e.g., ['facebook', 'twitter']).
     * @param string $linkedinSummary Optional summary text for LinkedIn shares.
     * @return array<string, string> An associative array of platform names and their corresponding share URLs.
     * @throws \InvalidArgumentException If the URL is invalid or platforms are unsupported.
     */
    public function getRawShareLinks(
        string $url,
        string $title,
        array $platforms = ['facebook', 'twitter', 'linkedin', 'whatsapp', 'telegram'],
        string $linkedinSummary = ''
    ): array;
}
