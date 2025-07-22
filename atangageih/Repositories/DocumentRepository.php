<?php

declare(strict_types=1);

namespace Atangageih\Repositories;

use App\Models\Docs\Document;
use App\Models\Modules\DocumentModule;
use Atangageih\Contracts\DocumentInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Repository for handling document-related operations.
 */
class DocumentRepository implements DocumentInterface
{
    /**
     * DocumentRepository constructor.
     */
    public function __construct()
    {
        // Constructor intentionally left empty for future extensibility
    }

    /**
     * Retrieve published documents of a specific type.
     *
     * @param string $type The document type to filter by
     * @return Collection<Document> Collection of published documents
     * @throws InvalidArgumentException If type is empty
     */
    public function getPublishedDocumentsByType(string $type): Collection
    {
        if (empty($type)) {
            throw new InvalidArgumentException('Document type cannot be empty');
        }

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
     * @throws InvalidArgumentException If type or slug is empty
     * @throws ModelNotFoundException If no matching document module is found
     */
    public function findModuleByTypeAndSlug(string $type, string $slug): ?DocumentModule
    {
        if (empty($type) || empty($slug)) {
            throw new InvalidArgumentException('Type and slug cannot be empty');
        }

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
     * @return void
     */
    private function addPublishedDocumentConstraints(Builder $query): void
    {
        $query->published()
            ->where(function (Builder $q): void {
                $q->where('published_at', '<=', now())
                    ->orWhereNull('published_at');
            });
    }
}
