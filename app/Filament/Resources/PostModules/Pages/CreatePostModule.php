<?php

namespace App\Filament\Resources\PostModules\Pages;

use App\Filament\Resources\PostModules\PostModuleResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePostModule extends CreateRecord
{
    protected static string $resource = PostModuleResource::class;
}
