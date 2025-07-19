<?php

namespace Atangageih\Contracts;

use App\Models\Modules\DocumentModule;
use Illuminate\Support\Collection;

interface DocumentInterface
{
    public function getPublishedDocumentsByType(string $type): Collection;

    public function findModuleByTypeAndSlug(string $type, string $slug): ?DocumentModule;
}
