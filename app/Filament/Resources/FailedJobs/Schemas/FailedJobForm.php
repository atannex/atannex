<?php

namespace App\Filament\Resources\FailedJobs\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class FailedJobForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('uuid')
                    ->label('UUID')
                    ->required(),
                Textarea::make('connection')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('queue')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('payload')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('exception')
                    ->required()
                    ->columnSpanFull(),
                DateTimePicker::make('failed_at')
                    ->required(),
            ]);
    }
}
