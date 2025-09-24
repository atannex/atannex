<?php

namespace App\Filament\Traits;

use Filament\Forms\Components\Slider;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;

class EngagementWeightsSection
{
    /**
     * Get the schema for the Engagement Weights section.
     *
     * @return \Filament\Forms\Components\Section
     */
    public static function make(): Section
    {
        return Section::make('Engagement Weights')
            ->schema([
                Grid::make(2)
                    ->schema([
                        Slider::make('weights.views')
                            ->label('Views Weight')
                            ->minValue(0)
                            ->maxValue(1)
                            ->step(0.1)
                            ->default(0.2)
                            ->reactive(),

                        Slider::make('weights.likes')
                            ->label('Likes Weight')
                            ->minValue(0)
                            ->maxValue(1)
                            ->step(0.1)
                            ->default(0.2)
                            ->reactive(),

                        Slider::make('weights.comments')
                            ->label('Comments Weight')
                            ->minValue(0)
                            ->maxValue(1)
                            ->step(0.1)
                            ->default(0.4)
                            ->reactive(),

                        Slider::make('weights.ratings')
                            ->label('Ratings Weight')
                            ->minValue(0)
                            ->maxValue(1)
                            ->step(0.1)
                            ->default(0.1)
                            ->reactive(),

                        Slider::make('weights.shares')
                            ->label('Shares Weight')
                            ->minValue(0)
                            ->maxValue(1)
                            ->step(0.1)
                            ->default(0.1)
                            ->reactive(),
                    ]),
            ])
            ->compact()
            ->collapsible()
            ->collapsed();
    }
}
