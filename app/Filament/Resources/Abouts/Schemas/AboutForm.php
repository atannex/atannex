<?php

namespace App\Filament\Resources\Abouts\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class AboutForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                TextInput::make('subtitle')
                    ->default(null),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('content')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('image')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('video')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('cta')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('cta_background')
                    ->default(null),
                TextInput::make('slug')
                    ->required(),
                TextInput::make('meta_title')
                    ->default(null),
                Textarea::make('meta_description')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('created_by')
                    ->numeric()
                    ->default(null),
                TextInput::make('updated_by')
                    ->numeric()
                    ->default(null),
                TextInput::make('flag')
                    ->required()
                    ->default('pending'),
            ]);
    }
}
