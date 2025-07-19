<?php

namespace App\Filament\Resources\PostViews;

use App\Filament\Resources\PostViews\Pages\CreatePostView;
use App\Filament\Resources\PostViews\Pages\EditPostView;
use App\Filament\Resources\PostViews\Pages\ListPostViews;
use App\Filament\Resources\PostViews\Schemas\PostViewForm;
use App\Filament\Resources\PostViews\Tables\PostViewsTable;
use App\Models\Pivots\PostView;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostViewResource extends Resource
{
    protected static ?string $model = PostView::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return PostViewForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PostViewsTable::configure($table);
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
            'index' => ListPostViews::route('/'),
            'create' => CreatePostView::route('/create'),
            'edit' => EditPostView::route('/{record}/edit'),
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
