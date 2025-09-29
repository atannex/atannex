<?php

declare(strict_types=1);

namespace Atannex\Services;

use App\Enums\Icon;
use Atannex\Contracts\ShareInterface;

/**
 * Service for generating social media share links.
 */
class ShareService
{
    /**
     * Constructor for ShareService.
     *
     * @param ShareInterface $shareRepository The repository for generating share links.
     */
    public function __construct(
        protected readonly ShareInterface $shareRepository
    ) {}

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
        return $this->shareRepository->getRawShareLinks(
            $url,
            $title,
            $platforms,
            $linkedinSummary
        );
    }
}
