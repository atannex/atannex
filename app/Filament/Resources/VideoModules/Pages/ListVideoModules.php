<?php

namespace App\Filament\Resources\VideoModules\Pages;

use App\Filament\Resources\VideoModules\VideoModuleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVideoModules extends ListRecords
{
    protected static string $resource = VideoModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
