<?php

namespace App\Filament\Resources\Caches\Pages;

use App\Filament\Resources\Caches\CacheResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCaches extends ListRecords
{
    protected static string $resource = CacheResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
