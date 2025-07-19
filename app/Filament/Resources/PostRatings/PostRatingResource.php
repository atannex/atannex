<?php

namespace App\Filament\Resources\PostRatings;

use App\Filament\Resources\PostRatings\Pages\CreatePostRating;
use App\Filament\Resources\PostRatings\Pages\EditPostRating;
use App\Filament\Resources\PostRatings\Pages\ListPostRatings;
use App\Filament\Resources\PostRatings\Schemas\PostRatingForm;
use App\Filament\Resources\PostRatings\Tables\PostRatingsTable;
use App\Models\Pivots\PostRating;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostRatingResource extends Resource
{
    protected static ?string $model = PostRating::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return PostRatingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PostRatingsTable::configure($table);
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
            'index' => ListPostRatings::route('/'),
            'create' => CreatePostRating::route('/create'),
            'edit' => EditPostRating::route('/{record}/edit'),
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
