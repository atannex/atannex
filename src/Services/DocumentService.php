<?php

declare(strict_types=1);

namespace Atannex\Services;

use App\Enums\Flag;
use App\Models\Docs\Document;
use App\Models\Modules\DocumentModule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

final class DocumentService
{
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

    private function basePublishedDocumentQuery(): Builder
    {
        return Document::query()->where(function (Builder $query): void {
            $this->addPublishedDocumentConstraints($query);
        });
    }

    private function addPublishedDocumentConstraints(Builder $query): void
    {
        $query->where('flag', Flag::PUBLISHED);
    }

    public function getRelatedDocuments(string $type, string $excludeSlug)
    {
        return $this->getPublishedDocumentsByType($type)
            ->filter(fn ($doc) => $doc->slug !== $excludeSlug)
            ->values()
            ->load(['author']);
    }
}
