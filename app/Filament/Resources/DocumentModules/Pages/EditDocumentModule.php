<?php

namespace App\Filament\Resources\DocumentModules\Pages;

use App\Filament\Resources\DocumentModules\DocumentModuleResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditDocumentModule extends EditRecord
{
    protected static string $resource = DocumentModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
