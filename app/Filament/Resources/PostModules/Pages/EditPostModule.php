<?php

namespace App\Filament\Resources\PostModules\Pages;

use App\Filament\Resources\PostModules\PostModuleResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditPostModule extends EditRecord
{
    protected static string $resource = PostModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
