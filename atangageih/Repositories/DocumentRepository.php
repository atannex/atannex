<?php

namespace Atangageih\Repositories;

use App\Models\Docs\Document;
use Illuminate\Support\Collection;
use App\Models\Modules\DocumentModule;
use Illuminate\Database\Eloquent\Builder;
use Atangageih\Contracts\DocumentInterface;

class DocumentRepository implements DocumentInterface
{
    public function __construct()
    {
        //
    }

    public function getPublishedDocumentsByType(string $type): Collection
    {
        return $this->basePublishedDocumentQuery()
            ->where('type', $type)
            ->with('modules')
            ->latest()
            ->get();
    }

    public function findModuleByTypeAndSlug(string $type, string $slug): ?DocumentModule
    {
        $documentConstraints = function (Builder $query) use ($type, $slug) {
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
     * Base query builder for published documents.
     */
    private function basePublishedDocumentQuery(): Builder
    {
        return Document::query()->where(function (Builder $query) {
            $this->addPublishedDocumentConstraints($query);
        });
    }

    /**
     * Shared constraints for published document flag and date.
     */
    private function addPublishedDocumentConstraints(Builder $query): void
    {
        $query->published()
            ->where(function ($q) {
                $q->where('published_at', '<=', now())
                    ->orWhereNull('published_at');
            });
    }
}
