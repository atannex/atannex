<?php

namespace App\Filament\Resources\PasswordResetTokens\Pages;

use App\Filament\Resources\PasswordResetTokens\PasswordResetTokenResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPasswordResetTokens extends ListRecords
{
    protected static string $resource = PasswordResetTokenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
