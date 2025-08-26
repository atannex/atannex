<?php

namespace App\Filament\Resources\Shares\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ShareForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('shareable_type')
                    ->required(),
                TextInput::make('shareable_id')
                    ->required()
                    ->numeric(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->default(null),
                TextInput::make('platform')
                    ->default(null),
                TextInput::make('share_count')
                    ->required()
                    ->numeric()
                    ->default(1),
                DateTimePicker::make('shared_at')
                    ->required(),
            ]);
    }
}
