<?php

namespace App\Filament\Resources\Contacts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ContactForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->default(null),
                TextInput::make('email')
                    ->email()
                    ->default(null),
                TextInput::make('phone')
                    ->tel()
                    ->default(null),
                TextInput::make('subject')
                    ->required(),
                Textarea::make('message')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('ip_address')
                    ->default(null),
                TextInput::make('user_agent')
                    ->default(null),
                TextInput::make('source')
                    ->default(null),
                DateTimePicker::make('read_at'),
                DateTimePicker::make('responded_at'),
                TextInput::make('flag')
                    ->required()
                    ->default('pending'),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->default(null),
            ]);
    }
}
