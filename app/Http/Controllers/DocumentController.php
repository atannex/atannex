<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Atangageih\Services\DocumentService;
use Illuminate\View\View;
use InvalidArgumentException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Controller for handling document-related HTTP requests.
 */
class DocumentController extends Controller
{
    /**
     * DocumentController constructor.
     *
     * @param DocumentService $documentService The document service instance
     */
    public function __construct(protected readonly DocumentService $documentService)
    {
    }

    /**
     * Display a listing of documents by type.
     *
     * @param string $type The document type to filter by
     * @return View The index view with documents
     * @throws InvalidArgumentException If type is empty
     */
    public function index(string $type): View
    {
        if (empty($type)) {
            throw new InvalidArgumentException('Document type cannot be empty');
        }

        $documents = $this->documentService->getDocumentsByType($type);

        return view('documents.index', compact('documents', 'type'));
    }

    /**
     * Display a specific document by type and slug.
     *
     * @param string $type The document type
     * @param string $slug The document slug
     * @return View The show view with document module and related documents
     * @throws InvalidArgumentException If type or slug is empty
     * @throws ModelNotFoundException If no matching document module is found
     */
    public function show(string $type, string $slug): View
    {
        if (empty($type) || empty($slug)) {
            throw new InvalidArgumentException('Type and slug cannot be empty');
        }

        $module = $this->documentService->getModuleByTypeAndSlug($type, $slug);
        $documents = $this->documentService->getDocumentsByType($type)
            ->filter(fn($doc) => $doc->slug !== $slug)
            ->values();

        return view('documents.show', compact('module', 'type', 'documents'));
    }
}
