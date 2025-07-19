<?php

namespace App\Filament\Resources\PasswordResetTokens\Pages;

use App\Filament\Resources\PasswordResetTokens\PasswordResetTokenResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPasswordResetToken extends EditRecord
{
    protected static string $resource = PasswordResetTokenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
