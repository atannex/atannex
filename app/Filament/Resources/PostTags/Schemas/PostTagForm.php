<?php

namespace App\Filament\Resources\PostTags\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PostTagForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('post_id')
                    ->relationship('post', 'title')
                    ->searchable()
                    ->preload()
                    ->default(null),
                Select::make('tag_id')
                    ->relationship('tag', 'name')
                    ->preload()
                    ->searchable()
                    ->default(null),
                TextInput::make('slug_path')
                    ->default(null),
            ]);
    }
}
