<?php

namespace App\Filament\Resources\Widgets\Schemas;

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
            ]);
    }
}
