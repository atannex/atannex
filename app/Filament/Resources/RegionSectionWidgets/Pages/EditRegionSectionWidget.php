<?php

namespace App\Filament\Resources\RegionSectionWidgets\Pages;

use App\Filament\Resources\RegionSectionWidgets\RegionSectionWidgetResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditRegionSectionWidget extends EditRecord
{
    protected static string $resource = RegionSectionWidgetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
