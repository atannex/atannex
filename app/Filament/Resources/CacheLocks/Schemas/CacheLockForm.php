<?php

namespace App\Filament\Resources\CacheLocks\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CacheLockForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('owner')
                    ->required(),
                TextInput::make('expiration')
                    ->required()
                    ->numeric(),
            ]);
    }
}
