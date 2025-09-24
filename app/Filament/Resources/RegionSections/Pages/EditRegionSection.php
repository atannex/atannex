<?php

namespace App\Filament\Resources\RegionSections\Pages;

use App\Filament\Resources\RegionSections\RegionSectionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditRegionSection extends EditRecord
{
    protected static string $resource = RegionSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
