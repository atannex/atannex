<?php

namespace App\Filament\Resources\Rulers\Schemas;

use App\Models\Regions\Ruler;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class RulerInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('slug'),
                ImageEntry::make('image')
                    ->placeholder('-'),
                TextEntry::make('dynasty')
                    ->placeholder('-'),
                TextEntry::make('title'),
                TextEntry::make('classification'),
                TextEntry::make('reign_start')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('reign_end')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('region.name')
                    ->label('Region'),
                TextEntry::make('phone')
                    ->placeholder('-'),
                TextEntry::make('email')
                    ->label('Email address')
                    ->placeholder('-'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('metadata')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('flag'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Ruler $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
