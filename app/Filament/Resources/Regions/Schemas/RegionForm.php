<?php

namespace App\Filament\Resources\Regions\Schemas;

use App\Enums\Flag;
use App\Enums\Territories;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class RegionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->disabled(),
                Select::make('flag')
                    ->required()
                    ->options(Flag::labels())
                    ->searchable()
                    ->preload()
                    ->default('pending'),
                Select::make('territory')
                    ->label('Territory')
                    ->options(Territories::labels())
                    ->searchable()
                    ->preload()
                    ->default(null),
                TextInput::make('logo')
                    ->default(null),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                Select::make('parent_id')
                    ->relationship('parent', 'name')
                    ->searchable()
                    ->preload()
                    ->default(null),
            ]);
    }
}
