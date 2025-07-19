<?php

namespace App\Filament\Resources\PostShares;

use App\Filament\Resources\PostShares\Pages\CreatePostShare;
use App\Filament\Resources\PostShares\Pages\EditPostShare;
use App\Filament\Resources\PostShares\Pages\ListPostShares;
use App\Filament\Resources\PostShares\Schemas\PostShareForm;
use App\Filament\Resources\PostShares\Tables\PostSharesTable;
use App\Models\Pivots\PostShare;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostShareResource extends Resource
{
    protected static ?string $model = PostShare::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return PostShareForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PostSharesTable::configure($table);
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
            'index' => ListPostShares::route('/'),
            'create' => CreatePostShare::route('/create'),
            'edit' => EditPostShare::route('/{record}/edit'),
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
