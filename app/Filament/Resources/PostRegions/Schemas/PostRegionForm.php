<?php

namespace App\Filament\Resources\PostRegions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class PostRegionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('post_id')
                    ->relationship('post', 'title')
                    ->default(null),
                Select::make('region_id')
                    ->relationship('region', 'name')
                    ->default(null),
            ]);
    }
}
