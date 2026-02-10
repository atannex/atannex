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
}
