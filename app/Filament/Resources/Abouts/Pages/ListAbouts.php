<?php

namespace App\Filament\Resources\Abouts\Pages;

use App\Filament\Resources\Abouts\AboutResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAbouts extends ListRecords
{
    protected static string $resource = AboutResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
