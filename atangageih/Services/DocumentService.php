<?php

namespace Atangageih\Services;

use App\Models\Modules\DocumentModule;
use Atangageih\Contracts\DocumentInterface;
use Illuminate\Support\Collection;

final class DocumentService
{
    public function __construct(protected readonly DocumentInterface $interface) {}


    public function getDocumentsByType(string $type): Collection
    {
        return $this->interface->getPublishedDocumentsByType($type);
    }

    public function getModuleByTypeAndSlug(string $type, string $slug): ?DocumentModule
    {
        return $this->interface->findModuleByTypeAndSlug($type, $slug);
    }
}
