<?php

namespace App\Filament\Resources\PostRatings\Pages;

use App\Filament\Resources\PostRatings\PostRatingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPostRatings extends ListRecords
{
    protected static string $resource = PostRatingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
