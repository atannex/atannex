<?php

namespace App\Filament\Resources\Documents\Pages;

use Illuminate\Support\Facades\Log;
use App\Events\Docs\DocumentCreated;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Documents\DocumentResource;

class CreateDocument extends CreateRecord
{
    protected static string $resource = DocumentResource::class;

    /**
     * Called after a record is created.
     */
    protected function afterCreate(): void
    {
        event(new DocumentCreated($this->record));
        Log::info('DocumentCreated event dispatched for document ID: ' . $this->record->id);
    }
}
