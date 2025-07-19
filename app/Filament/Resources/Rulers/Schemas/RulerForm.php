<?php

namespace App\Filament\Resources\Rulers\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class RulerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug'),
                FileUpload::make('image')
                    ->image()
                    ->required(),
                TextInput::make('traditional_title')
                    ->default(null),
                Select::make('region_id')
                    ->relationship('region', 'name')
                    ->required(),
                Select::make('rank')
                    ->options([
                        '1st Class' => '1st class',
                        '2nd Class' => '2nd class',
                        '3rd Class' => '3rd class',
                        'Unclassified' => 'Unclassified',
                    ])
                    ->default('Unclassified')
                    ->required(),
                DatePicker::make('reign_start'),
                DatePicker::make('reign_end'),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('flag')
                    ->required()
                    ->default('pending'),
                Textarea::make('metadata')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('dynasty')
                    ->default(null),
                TextInput::make('phone')
                    ->tel()
                    ->default(null),
                TextInput::make('email')
                    ->email()
                    ->default(null),
            ]);
    }
}
