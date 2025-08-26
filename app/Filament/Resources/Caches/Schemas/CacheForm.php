<?php

namespace App\Filament\Resources\Caches\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CacheForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('value')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('expiration')
                    ->required()
                    ->numeric(),
            ]);
    }
}
