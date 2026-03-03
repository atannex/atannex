<?php

namespace App\Http\Controllers;

use Atannex\Binders\HasView;
use Atannex\Concerns\HasDocument;
use Atannex\Services\RegionService;
use Atannex\Traits\HandlesPostDateResolution;
use Illuminate\View\View;

class RegionController extends Controller
{
    use HandlesPostDateResolution;
    use HasDocument;

    protected const SUPPORTED_DOCUMENT_TYPES = [
        'privacy',
        'terms',
        'faq',
        'guidelines',
        'help-center',
    ];

    public function __construct(
        protected readonly RegionService $regionService,
        protected readonly HasView $viewBinder,
    ) {}

    /*
    |--------------------------------------------------------------------------
    | Main Slug Resolver
    |--------------------------------------------------------------------------
    */

    public function resolve(string $slug): View
    {
        // 1️⃣ Document listings
        if ($response = $this->resolveDocumentListing($slug)) {
            return $response;
        }

        // 2️⃣ Single document
        if ($response = $this->resolveSingleDocument($slug)) {
            return $response;
        }

        // 3️⃣ Date archive
        if ($response = $this->resolveArchive($slug)) {
            return $response;
        }

        // 4️⃣ Content resolvers (post → author → tag → category)
        if ($response = $this->resolveContentEntities($slug)) {
            return $response;
        }

        // 5️⃣ Region (lowest specificity)
        if ($response = $this->resolveRegion($slug)) {
            return $response;
        }

        abort(404);
    }

    /*
    |--------------------------------------------------------------------------
    | Resolution Layers
    |--------------------------------------------------------------------------
    */

    protected function resolveDocumentListing(string $slug): ?View
    {
        if (! $this->isSupportedDocumentType($slug) && $slug !== 'testimonials') {
            return null;
        }

        return view('documents.index', [
            'documents' => $this->getDocumentsBySlug($slug),
            'type' => $slug,
            'isValidDocumentType' => $this->isSupportedDocumentType($slug),
            'isTestimonialType' => $slug === 'testimonials',
        ]);
    }

    protected function resolveSingleDocument(string $slug): ?View
    {
        if (! document_exists($slug)) {
            return null;
        }

        $document = $this->getDocumentByPath($slug);
        $module = $this->getDocumentModule($slug);

        abort_if(! $module, 404);

        return view('documents.show', [
            'module' => $module,
            'seoTitle' => $document->title,
            'type' => $document->slug,
            'documents' => $this->getRelatedDocuments($slug),
        ]);
    }

    protected function resolveArchive(string $slug): ?View
    {
        $archive = $this->resolvePostArchiveBySlug($slug);

        if (! $archive) {
            return null;
        }

        return $this->viewBinder
            ->renderDateView($archive['year'], $archive['month'], $archive['type']);
    }

    protected function resolveContentEntities(string $slug): ?View
    {
        if (post_exists($slug)) {
            return $this->viewBinder->renderPostShow($slug);
        }

        if (author_exists($slug)) {
            return $this->viewBinder->renderAuthorView($slug);
        }

        if (tag_exists($slug)) {
            return $this->viewBinder->renderTagView($slug);
        }

        if (category_exists($slug)) {
            return $this->viewBinder->renderCategoryView($slug);
        }

        return null;
    }

    protected function resolveRegion(string $slug): ?View
    {
        $region = $this->regionService->getRegionBySlug($slug);

        if (! $region) {
            return null;
        }

        return $this->viewBinder->renderRegionView($region);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    protected function isSupportedDocumentType(string $slug): bool
    {
        return in_array($slug, self::SUPPORTED_DOCUMENT_TYPES, true);
    }
}
