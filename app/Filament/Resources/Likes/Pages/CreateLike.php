<?php

namespace App\Filament\Resources\Likes\Pages;

use App\Filament\Resources\Likes\LikeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLike extends CreateRecord
{
    protected static string $resource = LikeResource::class;
}
