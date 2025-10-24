<?php

namespace App\Filament\Resources\RegionSectionWidgets\Pages;

use App\Filament\Resources\RegionSectionWidgets\RegionSectionWidgetResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRegionSectionWidgets extends ListRecords
{
    protected static string $resource = RegionSectionWidgetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
