<?php

namespace App\Filament\Resources\PostModules\Pages;

use App\Filament\Resources\PostModules\PostModuleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPostModules extends ListRecords
{
    protected static string $resource = PostModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
