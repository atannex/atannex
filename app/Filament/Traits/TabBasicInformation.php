<?php

namespace App\Filament\Traits;

use App\Enums\Sorting;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;

/**
 * Defines the schema for the "Basic Information" tab
 * in Filament admin panels.
 */
class TabBasicInformation
{
    /**
     * Build the Basic Information section schema.
     */
    public static function make(): Section
    {
        return Section::make('Basic Information')
            ->schema([
                Grid::make(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Tab Title')
                            ->placeholder('e.g., Breaking News, Latest Updates')
                            ->helperText('The display name of this tab in the interface.')
                            ->maxLength(50),

                        TextInput::make('sub_title')
                            ->label('Sub Title Tab')
                            ->placeholder('e.g., Breaking News, Latest Updates')
                            ->helperText('The display name of this tab in the interface.')
                            ->maxLength(50),

                        TextInput::make('limit')
                            ->label('Max number of categories')
                            ->numeric()
                            ->default(5)
                            ->minValue(1)
                            ->maxValue(50)
                            ->suffix('posts')
                            ->helperText('Maximum number of posts to display in this tab.'),

                        TextInput::make('relation_limit')
                            ->label('Posts Limit per category')
                            ->numeric()
                            ->default(5)
                            ->minValue(1)
                            ->maxValue(50)
                            ->suffix('posts')
                            ->helperText('Maximum number of posts to display in this tab.'),

                        TextInput::make('leaf_relation_limit')
                            ->label('Posts per leaf category')
                            ->numeric()
                            ->default(5)
                            ->minValue(1)
                            ->maxValue(50)
                            ->suffix('posts')
                            ->helperText('Maximum number of posts to display for each child element.'),

                        Select::make('sort')
                            ->label('Sort By')
                            ->options(Sorting::asSelectArray())
                            ->default(Sorting::CREATED_AT)
                            ->preload()
                            ->searchable()
                            ->helperText('Select the field by which content should be sorted.'),

                        Select::make('order')
                            ->label('Sort Order')
                            ->searchable()
                            ->preload()
                            ->options([
                                'asc' => 'Ascending',
                                'desc' => 'Descending',
                            ])
                            ->default('desc')
                            ->helperText('Choose the sorting direction.'),
                    ]),
            ])
            ->compact();
    }
}
