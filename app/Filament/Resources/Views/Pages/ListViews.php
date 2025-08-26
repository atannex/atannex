<?php

namespace App\Filament\Resources\Views\Pages;

use App\Filament\Resources\Views\ViewResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListViews extends ListRecords
{
    protected static string $resource = ViewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
