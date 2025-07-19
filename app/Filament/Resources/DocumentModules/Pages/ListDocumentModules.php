<?php

namespace App\Filament\Resources\DocumentModules\Pages;

use App\Filament\Resources\DocumentModules\DocumentModuleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDocumentModules extends ListRecords
{
    protected static string $resource = DocumentModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
