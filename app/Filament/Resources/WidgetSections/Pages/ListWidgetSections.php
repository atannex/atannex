<?php

namespace App\Filament\Resources\WidgetSections\Pages;

use App\Filament\Resources\WidgetSections\WidgetSectionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWidgetSections extends ListRecords
{
    protected static string $resource = WidgetSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
