<?php

namespace App\Filament\Resources\PageSections\Traits;

use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class TabBasicInformation
{
    /**
     * Get the schema for the Tab Basic Information section.
     *
     * @return \Filament\Forms\Components\Section
     */
    public static function make(): Section
    {
        return Section::make('Tab Basic Information')
            ->schema([
                Grid::make(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Tab Title')
                            ->placeholder('e.g., Breaking News, Latest Updates')
                            ->helperText('Display name for this tab')
                            ->maxLength(50)
                            ->required(),

                        TextInput::make('limit')
                            ->label('Content Limit')
                            ->numeric()
                            ->default(5)
                            ->minValue(1)
                            ->maxValue(50)
                            ->suffix('posts')
                            ->helperText('Maximum number of posts to display')
                            ->required(),
                    ]),
            ])
            ->compact();
    }
}
