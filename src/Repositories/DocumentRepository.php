<?php

namespace Atannex\Repositories;

use App\Enums\Flag;
use App\Models\Docs\Document;
use Illuminate\Support\Collection;
use App\Models\Modules\DocumentModule;
use Atannex\Contracts\DocumentInterface;
use Illuminate\Database\Eloquent\Builder;

/**
 * Repository for handling document-related operations.
 */
class DocumentRepository implements DocumentInterface
{
    /**
     * Retrieve published documents of a specific type.
     *
     * @param string $type The document type to filter by
     * @return Collection<Document> Collection of published documents
     */
    public function getPublishedDocumentsByType(string $type): Collection
    {
        return $this->basePublishedDocumentQuery()
            ->where('type', $type)
            ->with('modules')
            ->latest()
            ->get();
    }

    /**
     * Find a document module by type and slug.
     *
     * @param string $type The document type
     * @param string $slug The document slug
     * @return DocumentModule|null The found document module or null
     */
    public function findModuleByTypeAndSlug(string $type, string $slug): ?DocumentModule
    {
        $documentConstraints = function (Builder $query) use ($type, $slug): void {
            $query->where('type', $type)
                ->where('slug', $slug);
            $this->addPublishedDocumentConstraints($query);
        };

        return DocumentModule::query()
            ->with('document')
            ->whereHas('document', $documentConstraints)
            ->firstOrFail();
    }

    /**
     * Build base query for published documents.
     *
     * @return Builder<Document> The base query builder instance
     */
    private function basePublishedDocumentQuery(): Builder
    {
        return Document::query()->where(function (Builder $query): void {
            $this->addPublishedDocumentConstraints($query);
        });
    }

    /**
     * Apply published document constraints to the query.
     *
     * @param Builder<Document> $query The query builder instance
     */
    private function addPublishedDocumentConstraints(Builder $query): void
    {
        $query->where('flag', Flag::PUBLISHED)
            ->where(function (Builder $q): void {
                $q->where('flag', Flag::PUBLISHED);
            });
    }
}
