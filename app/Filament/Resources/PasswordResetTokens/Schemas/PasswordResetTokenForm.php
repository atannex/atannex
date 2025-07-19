<?php

namespace App\Filament\Resources\PasswordResetTokens\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PasswordResetTokenForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('token')
                    ->required(),
            ]);
    }
}
