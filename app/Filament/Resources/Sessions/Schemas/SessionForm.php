<?php

namespace App\Filament\Resources\Sessions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SessionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->default(null),
                TextInput::make('ip_address')
                    ->default(null),
                Textarea::make('user_agent')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('payload')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('last_activity')
                    ->required()
                    ->numeric(),
            ]);
    }
}
