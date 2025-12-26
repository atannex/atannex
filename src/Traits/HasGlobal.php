<?php

namespace Atannex\Traits;

trait HasGlobal
{
    /**
     * Supported document listing types.
     */
    protected const SUPPORTED_DOCUMENT_TYPES = [
        'privacy',
        'terms',
        'faq',
        'guidelines',
        'help-center',
    ];

    /**
     * Determine whether the given document type is supported.
     */
    protected function isSupportedDocumentType(string $slug): bool
    {
        return in_array($slug, self::SUPPORTED_DOCUMENT_TYPES, true);
    }

    /**
     * Extract year and month parts from a slug.
     *
     * Expected formats:
     *  - "2024/05"
     *  - "2024"
     */
    protected function extractDateParts(string $slug): array
    {
        [$year, $month] = array_pad(
            explode('/', trim($slug, '/'), 2),
            2,
            null
        );

        return [$year, $month];
    }

    /**
     * Validate a four-digit year.
     */
    protected function isValidYear(?string $year): bool
    {
        return $year !== null && preg_match('/^\d{4}$/', $year) === 1;
    }

    /**
     * Validate a two-digit month (01–12).
     */
    protected function isValidMonth(?string $month): bool
    {
        return $month !== null && preg_match('/^(0[1-9]|1[0-2])$/', $month) === 1;
    }
}
