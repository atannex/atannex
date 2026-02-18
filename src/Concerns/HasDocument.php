<?php

namespace Atannex\Concerns;

use App\Enums\Flag;
use App\Models\Docs\Document;
use App\Models\Modules\DocumentModule;
use Illuminate\Support\Collection;

trait HasDocument
{
    /**
     * Get documents by type (listing pages).
     */
    protected function getDocumentsBySlug(string $type): Collection
    {
        return Document::query()
            ->flagged(Flag::PUBLISHED)
            ->where('type', $type)
            ->with('author.user')
            ->latest()
            ->get();
    }

    /**
     * Retrieve a single document or fail.
     */
    protected function getDocumentByPath(string $slug): Document
    {
        return Document::query()
            ->flagged(Flag::PUBLISHED)
            ->where('slug_path', $slug)
            ->firstOrFail();
    }

    /**
     * Retrieve the document module with its relations.
     */
    protected function getDocumentModule(string $slug): DocumentModule
    {
        return DocumentModule::query()
            ->with(['document.author.user'])
            ->whereHas('document', function ($query) use ($slug) {
                $query->flagged(Flag::PUBLISHED)
                    ->where('slug_path', $slug);
            })
            ->firstOrFail();
    }

    /**
     * Get related documents excluding the current one.
     */
    protected function getRelatedDocuments(string $slug): Collection
    {
        return Document::query()
            ->flagged(Flag::PUBLISHED)
            ->where('slug_path', '!=', $slug)
            ->latest()
            ->limit(5)
            ->get();
    }
}
