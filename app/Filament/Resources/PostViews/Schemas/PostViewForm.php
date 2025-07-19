<?php

namespace App\Filament\Resources\PostViews\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PostViewForm
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
                TextInput::make('ip_address')
                    ->default(null),
                TextInput::make('user_agent')
                    ->default(null),
                TextInput::make('session_id')
                    ->default(null),
                TextInput::make('referer_url')
                    ->default(null),
            ]);
    }
}
