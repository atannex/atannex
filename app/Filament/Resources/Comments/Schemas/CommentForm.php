<?php

namespace App\Filament\Resources\Comments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CommentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('post_id')
                    ->relationship('post', 'title')
                    ->required(),
                Select::make('parent_id')
                    ->relationship('parent', 'id')
                    ->default(null),
                Textarea::make('comment')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                TextInput::make('ip_address')
                    ->default(null),
                TextInput::make('ip_country')
                    ->default(null),
                Textarea::make('user_agent')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('likes_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('dislikes_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('replies_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                DateTimePicker::make('edited_at'),
                TextInput::make('edited_reason')
                    ->default(null),
                TextInput::make('edited_by')
                    ->numeric()
                    ->default(null),
                TextInput::make('deleted_by')
                    ->numeric()
                    ->default(null),
                DateTimePicker::make('reviewed_at'),
                TextInput::make('reviewed_by')
                    ->numeric()
                    ->default(null),
                Textarea::make('moderation_notes')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
