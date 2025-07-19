<?php

namespace App\Filament\Resources\CacheLocks\Pages;

use App\Filament\Resources\CacheLocks\CacheLockResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCacheLocks extends ListRecords
{
    protected static string $resource = CacheLockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
