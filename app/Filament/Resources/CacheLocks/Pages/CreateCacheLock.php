<?php

namespace App\Filament\Resources\CacheLocks\Pages;

use App\Filament\Resources\CacheLocks\CacheLockResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCacheLock extends CreateRecord
{
    protected static string $resource = CacheLockResource::class;
}
