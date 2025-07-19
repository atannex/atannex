<?php

namespace App\Filament\Resources\Rulers\Pages;

use App\Filament\Resources\Rulers\RulerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRulers extends ListRecords
{
    protected static string $resource = RulerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
