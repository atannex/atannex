<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\Documents\DocumentResource;
use Atangageih\Services\DocumentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class DocumentController extends Controller
{
    private const VALID_DOCUMENT_TYPES = [
        'privacy',
        'terms',
        'faq',
        'guidelines',
        'testimonials',
        'help-center',
    ];

    public function __construct(
        private readonly DocumentService $documentService
    ) {}

    public function index(string $type): JsonResponse
    {
        $this->validateDocumentType($type);

        $documents = $this->documentService->getDocumentsByType($type)
            ->load(['author', 'modules']);

        return response()->json([
            'data' => DocumentResource::collection($documents),
            'meta' => [
                'type' => $type,
                'count' => $documents->count(),
            ],
        ]);
    }

    public function show(string $type, string $slug): JsonResponse
    {
        $this->validateDocumentType($type);

        $document = $this->documentService->getDocumentByTypeAndSlug($type, $slug)
            ->load(['author', 'modules']);

        $relatedDocuments = $this->documentService->getDocumentsByType($type)
            ->where('slug', '!=', $slug)
            ->values()
            ->load(['author']);

        return response()->json([
            'data' => new DocumentResource($document),
            'related' => DocumentResource::collection($relatedDocuments),
        ]);
    }

    private function validateDocumentType(string $type): void
    {
        Validator::make(
            ['type' => $type],
            ['type' => ['required', 'string', 'in:' . implode(',', self::VALID_DOCUMENT_TYPES)]]
        )->validate();
    }
}
