<?php

namespace App\Filament\Resources\Jobs\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class JobForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('queue')
                    ->required(),
                Textarea::make('payload')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('attempts')
                    ->required()
                    ->numeric(),
                TextInput::make('reserved_at')
                    ->numeric()
                    ->default(null),
                TextInput::make('available_at')
                    ->required()
                    ->numeric(),
            ]);
    }
}
