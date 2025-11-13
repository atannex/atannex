<?php

declare(strict_types=1);

namespace Atannex\Services;

use App\Models\Modules\DocumentModule;
use Atannex\Contracts\DocumentInterface;
use Illuminate\Support\Collection;

/**
 * Service class for handling document-related operations.
 */
final class DocumentService
{
    /**
     * DocumentService constructor.
     *
     * @param  DocumentInterface  $interface  The document repository interface
     */
    public function __construct(protected readonly DocumentInterface $interface)
    {
        // Constructor intentionally left empty as dependency is injected
    }

    /**
     * Retrieve published documents of a specific type.
     *
     * @param  string  $type  The document type to filter by
     * @return Collection<DocumentModule> Collection of published documents
     */
    public function getDocumentsByType(string $type): Collection
    {
        return $this->interface->getPublishedDocumentsByType($type);
    }

    /**
     * Find a document module by type and slug.
     *
     * @param  string  $type  The document type
     * @param  string  $slug  The document slug
     * @return DocumentModule|null The found document module or null
     */
    public function getDocumentByTypeAndSlug(string $type, string $slug): ?DocumentModule
    {
        return $this->interface->findModuleByTypeAndSlug($type, $slug);
    }

    public function getRelatedDocuments(string $type, string $excludeSlug)
    {
        return $this->getDocumentsByType($type)
            ->filter(fn ($doc) => $doc->slug !== $excludeSlug)
            ->values()
            ->load(['author']);
    }
}
