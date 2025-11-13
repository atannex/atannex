<?php

namespace App\Filament\Resources\Widgets\Schemas;

use App\Enums\Flag;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class WidgetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('slug')
                    ->disabled(),
                TextInput::make('name')
                    ->required(),
                Select::make('flag')
                    ->options(Flag::labels())
                    ->preload()
                    ->searchable()
                    ->required()
                    ->default(Flag::PENDING),
            ]);
    }
}
