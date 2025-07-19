<?php

namespace App\Filament\Resources\PostShares\Pages;

use App\Filament\Resources\PostShares\PostShareResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditPostShare extends EditRecord
{
    protected static string $resource = PostShareResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
