<?php

namespace App\Filament\Resources\PageSections\Traits;

use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;

class AdditionalSettingsSection
{
    /**
     * Get the schema for the Additional Settings section.
     *
     * @return \Filament\Forms\Components\Section
     */
    public static function make(): Section
    {
        return Section::make('Additional Settings')
            ->schema([
                Grid::make(1)
                    ->schema([
                        Textarea::make('description')
                            ->label('Internal Notes')
                            ->placeholder('Add internal notes about this tab (not visible to users)')
                            ->rows(2)
                            ->helperText('For administrative purposes only')
                            ->columnSpanFull(),
                    ]),
            ])
            ->compact()
            ->collapsible()
            ->collapsed();
    }
}
