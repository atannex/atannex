<?php

namespace App\Filament\Resources\RegionSections\Pages;

use App\Filament\Resources\RegionSections\RegionSectionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRegionSections extends ListRecords
{
    protected static string $resource = RegionSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
