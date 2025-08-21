<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Atannex\Services\DocumentService;

class DocumentController extends Controller
{
    /**
     * Supported document types for the site.
     */
    protected array $supportedTypes = [
        'privacy',
        'terms',
        'faq',
        'guidelines',
        'help-center',
    ];

    public function __construct(
        protected readonly DocumentService $documentService
    ) {}

    /**
     * Display a list of documents for a given type.
     */
    public function index(Request $request, string $type): View
    {
        $documents = $this->documentService->getDocumentsByType($type);

        return view('documents.index', [
            'documents'            => $documents,
            'type'                 => $type,
            'isValidDocumentType'  => $this->isSupportedType($type, $request),
            'isTestimonialType'    => $this->isTestimonialType($type, $request),
        ]);
    }

    /**
     * Display a single document and related documents for the same type.
     */
    public function show(string $type, string $slug): View
    {
        return view('documents.show', [
            'module'    => $this->documentService->getDocumentByTypeAndSlug($type, $slug),
            'type'      => $type,
            'documents' => $this->getRelatedDocuments($type, $slug),
        ]);
    }

    /**
     * Determine if the given type is a supported document type.
     */
    protected function isSupportedType(string $type, Request $request): bool
    {
        return $request->routeIs('document.index') && in_array($type, $this->supportedTypes, true);
    }

    /**
     * Determine if the given type is a testimonial.
     */
    protected function isTestimonialType(string $type, Request $request): bool
    {
        return $request->routeIs('document.index') && $type === 'testimonials';
    }

    /**
     * Retrieve related documents by type, excluding the given slug.
     */
    protected function getRelatedDocuments(string $type, string $slug)
    {
        return $this->documentService
            ->getDocumentsByType($type)
            ->filter(fn($doc) => $doc->slug !== $slug)
            ->values();
    }
}
