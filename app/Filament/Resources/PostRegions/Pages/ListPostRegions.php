<?php

namespace App\Filament\Resources\PostRegions\Pages;

use App\Filament\Resources\PostRegions\PostRegionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPostRegions extends ListRecords
{
    protected static string $resource = PostRegionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
