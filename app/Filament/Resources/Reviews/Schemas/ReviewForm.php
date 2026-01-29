<?php

namespace App\Filament\Resources\Reviews\Schemas;

use App\Enums\Flag;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('reviewable_type')
                    ->required(),
                TextInput::make('reviewable_id')
                    ->required()
                    ->numeric(),
                Textarea::make('content')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('reviewer_name')
                    ->default(null),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->default(null),
                TextInput::make('reviewer_rating')
                    ->required()
                    ->numeric(),
                Select::make('flag')
                    ->options(Flag::asSelectArray())
                    ->preload()
                    ->searchable()
                    ->required()
                    ->default(Flag::PENDING_REVIEW),
                TextInput::make('ip_address')
                    ->default(null),
            ]);
    }
}
