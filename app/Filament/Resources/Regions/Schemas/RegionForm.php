<?php

namespace App\Filament\Resources\Regions\Schemas;

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
                    ->required(),
                TextInput::make('flag')
                    ->required()
                    ->default('pending'),

                Select::make('type')
                    ->label('Village Type')
                    ->options([
                        'Fondom'   => 'Fondom',
                        'Chiefdom' => 'Chiefdom',
                        'Village'  => 'Village',
                        'Quarter'  => 'Quarter',
                        'Division' => 'Division',
                        'Sub-Division' => 'Sub-Division',
                    ])
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
