<?php

namespace App\Filament\Resources\CategorySections\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
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
            ]);
    }
}
