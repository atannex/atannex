<?php

namespace App\Filament\Resources\JobBatches\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class JobBatchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('total_jobs')
                    ->required()
                    ->numeric(),
                TextInput::make('pending_jobs')
                    ->required()
                    ->numeric(),
                TextInput::make('failed_jobs')
                    ->required()
                    ->numeric(),
                Textarea::make('failed_job_ids')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('options')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('cancelled_at')
                    ->numeric()
                    ->default(null),
                TextInput::make('finished_at')
                    ->numeric()
                    ->default(null),
            ]);
    }
}
