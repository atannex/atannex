<?php

namespace App\Filament\Resources\Documents\Pages;

use App\Events\Docs\DocumentCreated;
use App\Filament\Resources\Documents\DocumentResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;

class CreateDocument extends CreateRecord
{
    protected static string $resource = DocumentResource::class;

    /**
     * Called after a record is created.
     */
    protected function afterCreate(): void
    {
        event(new DocumentCreated($this->record));
        Log::info('DocumentCreated event dispatched for document ID: '.$this->record->id);
    }
}
