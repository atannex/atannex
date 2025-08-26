<?php

namespace App\Filament\Resources\Caches;

use App\Filament\Resources\Caches\Pages\CreateCache;
use App\Filament\Resources\Caches\Pages\EditCache;
use App\Filament\Resources\Caches\Pages\ListCaches;
use App\Filament\Resources\Caches\Schemas\CacheForm;
use App\Filament\Resources\Caches\Tables\CachesTable;
use App\Models\Controls\Cache;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CacheResource extends Resource
{
    protected static ?string $model = Cache::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return CacheForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CachesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCaches::route('/'),
            'create' => CreateCache::route('/create'),
            'edit' => EditCache::route('/{record}/edit'),
        ];
    }
}
