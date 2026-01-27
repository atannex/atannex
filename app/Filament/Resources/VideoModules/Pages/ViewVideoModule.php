<?php

namespace App\Filament\Resources\VideoModules\Pages;

use App\Filament\Resources\VideoModules\VideoModuleResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewVideoModule extends ViewRecord
{
    protected static string $resource = VideoModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
