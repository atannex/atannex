<?php

namespace App\Filament\Resources\CategorySections\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class CategorySectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->description('Configure the category and section relationship')
                    ->schema([
                        Select::make('category_id')
                            ->label('Category')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->placeholder('Select a category')
                            ->helperText('Choose the parent category for this section'),

                        Select::make('section_id')
                            ->label('Section')
                            ->relationship('section', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->placeholder('Select a section (optional)')
                            ->helperText('Associate with a specific section if needed'),
                    ])
                    ->columns(2),

                Section::make('Configuration')
                    ->description('Additional settings and positioning')
                    ->schema([
                        Textarea::make('config')
                            ->label('Configuration Data')
                            ->placeholder('Enter JSON configuration or custom settings...')
                            ->rows(4)
                            ->nullable()
                            ->helperText('Optional JSON configuration for custom behavior')
                            ->columnSpanFull(),

                        TextInput::make('position')
                            ->label('Display Position')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->step(1)
                            ->required()
                            ->helperText('Order in which this item appears (0 = first)'),

                        Toggle::make('is_active')
                            ->label('Active Status')
                            ->default(true)
                            ->required()
                            ->helperText('Enable or disable this category section'),
                    ])
                    ->columns(2),
            ]);
    }
}
