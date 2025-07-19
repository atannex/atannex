<?php

namespace App\Filament\Resources\CacheLocks\Pages;

use App\Filament\Resources\CacheLocks\CacheLockResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCacheLock extends EditRecord
{
    protected static string $resource = CacheLockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
