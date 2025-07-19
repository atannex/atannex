<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('slug')
                    ->disabled(),
                TextInput::make('title')
                    ->required(),
                Select::make('parent_id')
                    ->relationship('parent', 'title')
                    ->default(null),
                Textarea::make('metadata')
                    ->default(null)
                    ->columnSpanFull(),
                DateTimePicker::make('published_at'),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
