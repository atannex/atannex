<?php

namespace App\Filament\Resources\CategorySections\Pages;

use App\Filament\Resources\CategorySections\CategorySectionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditCategorySection extends EditRecord
{
    protected static string $resource = CategorySectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
