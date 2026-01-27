<?php

namespace App\Filament\Resources\VideoModules\Schemas;

use App\Models\Posts\VideoModule;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class VideoModuleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('content')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('images')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('video.title')
                    ->label('Video'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (VideoModule $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
