<?php

namespace App\Filament\Resources\PostShares\Pages;

use App\Filament\Resources\PostShares\PostShareResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePostShare extends CreateRecord
{
    protected static string $resource = PostShareResource::class;
}
