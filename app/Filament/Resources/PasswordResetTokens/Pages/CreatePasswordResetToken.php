<?php

namespace App\Filament\Resources\PasswordResetTokens\Pages;

use App\Filament\Resources\PasswordResetTokens\PasswordResetTokenResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePasswordResetToken extends CreateRecord
{
    protected static string $resource = PasswordResetTokenResource::class;
}
