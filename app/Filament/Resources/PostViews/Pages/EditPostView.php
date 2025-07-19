<?php

namespace App\Filament\Resources\PostViews\Pages;

use App\Filament\Resources\PostViews\PostViewResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditPostView extends EditRecord
{
    protected static string $resource = PostViewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
