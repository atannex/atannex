<?php

namespace App\Filament\Resources\WidgetSections;

use App\Filament\Resources\WidgetSections\Pages\CreateWidgetSection;
use App\Filament\Resources\WidgetSections\Pages\EditWidgetSection;
use App\Filament\Resources\WidgetSections\Pages\ListWidgetSections;
use App\Filament\Resources\WidgetSections\Schemas\WidgetSectionForm;
use App\Filament\Resources\WidgetSections\Tables\WidgetSectionsTable;
use App\Models\Pivots\WidgetSection;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WidgetSectionResource extends Resource
{
    protected static ?string $model = WidgetSection::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return WidgetSectionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WidgetSectionsTable::configure($table);
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
            'index' => ListWidgetSections::route('/'),
            'create' => CreateWidgetSection::route('/create'),
            'edit' => EditWidgetSection::route('/{record}/edit'),
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
