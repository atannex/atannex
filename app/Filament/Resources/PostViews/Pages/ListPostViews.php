<?php

namespace App\Filament\Resources\PostViews\Pages;

use App\Filament\Resources\PostViews\PostViewResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPostViews extends ListRecords
{
    protected static string $resource = PostViewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
