<?php

declare(strict_types=1);

namespace Atannex\Services;

use App\Models\Modules\DocumentModule;
use Illuminate\Support\Collection;
use App\Enums\Flag;
use App\Models\Docs\Document;
use Illuminate\Database\Eloquent\Builder;

/**
 * Service class for handling document-related operations.
 */
final class DocumentService
{
    /**
     * Retrieve published documents of a specific type.
     *
     * @param  string  $type  The document type to filter by
     * @return Collection<Document>
     */
    public function getPublishedDocumentsByType(string $type): Collection
    {
        return $this->basePublishedDocumentQuery()
            ->where('type', $type)
            ->with([
                'modules',
                'author.user',
            ])
            ->latest()
            ->get();
    }

    /**
     * Find a document module by type and slug.
     */
    public function findModuleByTypeAndSlug(string $type, string $slug): ?DocumentModule
    {
        $documentConstraints = function (Builder $query) use ($type, $slug): void {
            $query->where('type', $type)
                ->where('slug', $slug);

            $this->addPublishedDocumentConstraints($query);
        };

        return DocumentModule::query()
            ->with([
                'document.author.user',
            ])
            ->whereHas('document', $documentConstraints)
            ->firstOrFail();
    }

    /**
     * Build base query for published documents.
     *
     * @return Builder<Document>
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
     * @param  Builder<Document>  $query
     */
    private function addPublishedDocumentConstraints(Builder $query): void
    {
        $query->where('flag', Flag::PUBLISHED);
    }

    public function getRelatedDocuments(string $type, string $excludeSlug)
    {
        return $this->getPublishedDocumentsByType($type)
            ->filter(fn($doc) => $doc->slug !== $excludeSlug)
            ->values()
            ->load(['author']);
    }
}
