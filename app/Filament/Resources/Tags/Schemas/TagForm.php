<?php

namespace App\Filament\Resources\Tags\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class TagForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->disabled(),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                Select::make('parent_id')
                    ->relationship('parent', 'name')
                    ->default(null),
            ]);
    }
}
