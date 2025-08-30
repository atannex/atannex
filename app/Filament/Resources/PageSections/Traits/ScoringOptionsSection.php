<?php

namespace App\Filament\Resources\PageSections\Traits;

use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class ScoringOptionsSection
{
    /**
     * Get the schema for the Scoring Options section.
     *
     * @return \Filament\Forms\Components\Section
     */
    public static function make(): Section
    {
        return Section::make('Scoring Options')
            ->schema([
                Grid::make(2)
                    ->schema([
                        TextInput::make('min_score')
                            ->label('Minimum Score')
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->helperText('Minimum engagement score for posts'),

                        Toggle::make('prioritize_recency')
                            ->label('Prioritize Recency')
                            ->default(true)
                            ->helperText('Boost scores for newer posts within the date range')
                            ->inline(false),
                    ]),
            ])
            ->compact();
    }
}
