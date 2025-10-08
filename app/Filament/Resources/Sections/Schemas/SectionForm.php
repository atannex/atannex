<?php

namespace App\Filament\Resources\Sections\Schemas;

use App\Enums\Flag;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('slug')
                    ->disabled()
                    ->readOnly(),
                TextInput::make('name')
                    ->required(),
                Select::make('flag')
                    ->default(Flag::PENDING)
                    ->searchable()
                    ->preload()
                    ->options(Flag::asSelectArray())
                    ->required(),
            ]);
    }
}
