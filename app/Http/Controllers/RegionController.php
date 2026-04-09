<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Atannex\Binders\HasView;
use Atannex\Concerns\HasDocument;
use Atannex\Services\RegionService;
use Illuminate\View\View;

class RegionController extends Controller
{
    use HasDocument;

    public function __construct(
        protected readonly RegionService $regionService,
        protected readonly HasView $viewBinder,
    ) {}

    /*
    |--------------------------------------------------------------------------
    | DOCUMENTS
    |--------------------------------------------------------------------------
    */

    public function documentListing(string $type): View
    {
        if (!$this->isSupportedDocumentType($type) && $type !== 'testimonials') {
            abort(404);
        }

        return view('documents.index', [
            'documents'           => $this->getDocumentsBySlug($type),
            'type'                => $type,
            'isValidDocumentType' => $this->isSupportedDocumentType($type),
            'isTestimonialType'   => $type === 'testimonials',
        ]);
    }

    public function singleDocument(string $slug): View
    {
        $document = $this->getDocumentByPath($slug);

        if (!$document) {
            abort(404);
        }

        $module = $this->getDocumentModule($slug);

        if (!$module) {
            abort(404);
        }

        return view('documents.show', [
            'module'    => $module,
            'seoTitle'  => $document->title,
            'type'      => $document->slug,
            'documents' => $this->getRelatedDocuments($slug),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | ARCHIVES
    |--------------------------------------------------------------------------
    */

    public function archiveYear(string $year): View
    {
        return $this->viewBinder->renderYearView($year);
    }

    public function archiveMonth(string $year, string $month): View
    {
        return $this->viewBinder->renderMonthView($year, $month);
    }

    /*
    |--------------------------------------------------------------------------
    | CONTENT ENTITIES
    |--------------------------------------------------------------------------
    */

    public function post(string $slug): View
    {
        return $this->viewBinder->renderPostShow($slug);
    }

    public function author(string $slug): View
    {
        return $this->viewBinder->renderAuthorView($slug);
    }

    public function tag(string $slug): View
    {
        return $this->viewBinder->renderTagView($slug);
    }

    public function category(string $slug): View
    {
        return $this->viewBinder->renderCategoryView($slug);
    }

    /*
    |--------------------------------------------------------------------------
    | REGIONS
    |--------------------------------------------------------------------------
    */

    public function region(string $slug): View
    {
        $region = $this->regionService->getRegionBySlug($slug);

        return $this->viewBinder->renderRegionView($region);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    protected function isSupportedDocumentType(string $type): bool
    {
        return in_array($type, [
            'privacy',
            'terms',
            'faq',
            'guidelines',
            'help-center',
        ], true);
    }
}
