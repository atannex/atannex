<?php

namespace App\Filament\Resources\Rulers\Pages;

use App\Filament\Resources\Rulers\RulerResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditRuler extends EditRecord
{
    protected static string $resource = RulerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
