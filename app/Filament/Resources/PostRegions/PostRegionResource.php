<?php

namespace App\Filament\Resources\PostRegions;

use App\Filament\Resources\PostRegions\Pages\CreatePostRegion;
use App\Filament\Resources\PostRegions\Pages\ListPostRegions;
use App\Filament\Resources\PostRegions\Schemas\PostRegionForm;
use App\Filament\Resources\PostRegions\Tables\PostRegionsTable;
use App\Models\Pivots\PostRegion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostRegionResource extends Resource
{
    protected static ?string $model = PostRegion::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return PostRegionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PostRegionsTable::configure($table);
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
            'index' => ListPostRegions::route('/'),
            'create' => CreatePostRegion::route('/create')
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
