<?php

namespace App\Filament\Resources\WidgetSections\Pages;

use App\Filament\Resources\WidgetSections\WidgetSectionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditWidgetSection extends EditRecord
{
    protected static string $resource = WidgetSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
