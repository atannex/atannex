<?php

namespace App\Filament\Resources\PostRatings\Pages;

use App\Filament\Resources\PostRatings\PostRatingResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditPostRating extends EditRecord
{
    protected static string $resource = PostRatingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
