<?php

namespace App\Filament\Resources\PostShares\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PostShareForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('post_id')
                    ->relationship('post', 'title')
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->default(null),
                TextInput::make('platform')
                    ->default(null),
                TextInput::make('share_url')
                    ->default(null),
                TextInput::make('share_count')
                    ->required()
                    ->numeric()
                    ->default(1),
                DateTimePicker::make('shared_at')
                    ->required(),
            ]);
    }
}
