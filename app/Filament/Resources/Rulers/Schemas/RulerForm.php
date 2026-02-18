<?php

namespace App\Filament\Resources\Rulers\Schemas;

use App\Enums\Flag;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;

class RulerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                FileUpload::make('image')
                    ->afterStateUpdated(function ($state, $record) {
                        if ($record && $record->image && $record->image !== $state) {
                            Storage::disk('public')->delete($record->image);
                        }
                    })
                    ->image(),
                TextInput::make('dynasty')
                    ->default(null),
                TextInput::make('title')
                    ->required()
                    ->default('mbe'),
                TextInput::make('classification')
                    ->required()
                    ->default('unclassified'),
                DatePicker::make('reign_start'),
                DatePicker::make('reign_end'),
                Select::make('region_id')
                    ->relationship('region', 'name')
                    ->preload()
                    ->searchable()
                    ->required(),
                TextInput::make('phone')
                    ->tel()
                    ->default(null),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->default(null),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('metadata')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('flag')
                    ->required()
                    ->default(Flag::DRAFT),
            ]);
    }
}
