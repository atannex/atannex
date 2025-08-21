<?php

namespace Atangageih\Contracts;

use App\Models\Modules\DocumentModule;
use Illuminate\Support\Collection;

/**
 * Interface DocumentInterface
 *
 * Defines the contract for document-related operations, including
 * fetching published documents by type and retrieving a document
 * module by type and slug.
 */
interface DocumentInterface
{
    /**
     * Retrieve all published documents of a given type.
     *
     * @param string $type The document type (e.g., 'report', 'guide').
     * @return Collection<DocumentModule> A collection of published document modules.
     */
    public function getPublishedDocumentsByType(string $type): Collection;

    /**
     * Find a specific document module based on its type and slug.
     *
     * @param string $type The type/category of the document.
     * @param string $slug The unique slug identifier of the document.
     * @return DocumentModule|null The matched document module, or null if not found.
     */
    public function findModuleByTypeAndSlug(string $type, string $slug): ?DocumentModule;
}
