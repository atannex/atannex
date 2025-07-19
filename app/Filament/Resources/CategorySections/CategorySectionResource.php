<?php

namespace App\Filament\Resources\CategorySections;

use App\Filament\Resources\CategorySections\Pages\CreateCategorySection;
use App\Filament\Resources\CategorySections\Pages\EditCategorySection;
use App\Filament\Resources\CategorySections\Pages\ListCategorySections;
use App\Filament\Resources\CategorySections\Schemas\CategorySectionForm;
use App\Filament\Resources\CategorySections\Tables\CategorySectionsTable;
use App\Models\Pivots\CategorySection;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategorySectionResource extends Resource
{
    protected static ?string $model = CategorySection::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return CategorySectionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CategorySectionsTable::configure($table);
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
            'index' => ListCategorySections::route('/'),
            'create' => CreateCategorySection::route('/create'),
            'edit' => EditCategorySection::route('/{record}/edit'),
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
