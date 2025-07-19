<?php

namespace App\Filament\Resources\CategorySections\Pages;

use App\Filament\Resources\CategorySections\CategorySectionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCategorySections extends ListRecords
{
    protected static string $resource = CategorySectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
