<?php

namespace App\Filament\Resources\PostRegions\Pages;

use App\Filament\Resources\PostRegions\PostRegionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPostRegion extends EditRecord
{
    protected static string $resource = PostRegionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
