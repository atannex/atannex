<?php

namespace App\Filament\Resources\Documents\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                TextInput::make('type')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                Select::make('author_id')
                    ->relationship('author', 'id')
                    ->default(null),
                TextInput::make('flag')
                    ->required()
                    ->default('pending'),
                DateTimePicker::make('published_at'),
            ]);
    }
}
