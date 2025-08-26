<?php

namespace App\Filament\Resources\Ratings\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RatingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('rateable_type')
                    ->required(),
                TextInput::make('rateable_id')
                    ->required()
                    ->numeric(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                TextInput::make('rating')
                    ->required()
                    ->numeric(),
                DateTimePicker::make('rated_at')
                    ->required(),
            ]);
    }
}
