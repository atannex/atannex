<?php

namespace App\Filament\Resources\Likes;

use App\Filament\Resources\Likes\Pages\CreateLike;
use App\Filament\Resources\Likes\Pages\EditLike;
use App\Filament\Resources\Likes\Pages\ListLikes;
use App\Filament\Resources\Likes\Schemas\LikeForm;
use App\Filament\Resources\Likes\Tables\LikesTable;
use App\Models\Interactions\Like;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LikeResource extends Resource
{
    protected static ?string $model = Like::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return LikeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LikesTable::configure($table);
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
            'index' => ListLikes::route('/'),
            'create' => CreateLike::route('/create'),
            'edit' => EditLike::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
