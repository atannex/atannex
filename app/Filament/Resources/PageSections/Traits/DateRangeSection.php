<?php

namespace App\Filament\Resources\PageSections\Traits;

use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DateTimePicker;

class DateRangeSection
{
    /**
     * Get the schema for the Date Range section.
     *
     * @return \Filament\Forms\Components\Section
     */
    public static function make(): Section
    {
        return Section::make('Date Range')
            ->schema([
                Grid::make(2)
                    ->schema([
                        DateTimePicker::make('start')
                            ->label('Start Date')
                            ->default(now()->startOfDay())
                            ->helperText('Start of the date range for posts'),

                        DateTimePicker::make('end')
                            ->label('End Date')
                            ->default(now()->endOfDay())
                            ->afterOrEqual('start')
                            ->helperText('End of the date range for posts'),
                    ]),
            ])
            ->compact();
    }
}
