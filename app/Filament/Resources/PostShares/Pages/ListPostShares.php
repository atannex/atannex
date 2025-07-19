<?php

namespace App\Filament\Resources\PostShares\Pages;

use App\Filament\Resources\PostShares\PostShareResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPostShares extends ListRecords
{
    protected static string $resource = PostShareResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
