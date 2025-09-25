<?php

namespace App\Filament\Resources\Rulers\Pages;

use App\Filament\Resources\Rulers\RulerResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewRuler extends ViewRecord
{
    protected static string $resource = RulerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
