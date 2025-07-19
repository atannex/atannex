<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Atangageih\Services\DocumentService;

class DocumentController extends Controller
{
    public function __construct(
        protected readonly DocumentService $privacyTermService
    ) {}

    public function index(string $type): View
    {
        $documents = $this->privacyTermService->getDocumentsByType($type);

        return view('documents.index', compact('documents', 'type'));
    }

    public function show(string $type, string $slug): View
    {
        $module = $this->privacyTermService->getModuleByTypeAndSlug($type, $slug);
        $documents = $this->privacyTermService->getDocumentsByType($type)
            ->filter(fn($doc) => $doc->slug !== $slug)
            ->values();

        return view('documents.show', compact('module', 'type', 'documents'));
    }
}
