<?php

namespace App\Filament\Resources\RegionSectionWidgets;

use App\Filament\Resources\RegionSectionWidgets\Pages\CreateRegionSectionWidget;
use App\Filament\Resources\RegionSectionWidgets\Pages\EditRegionSectionWidget;
use App\Filament\Resources\RegionSectionWidgets\Pages\ListRegionSectionWidgets;
use App\Filament\Resources\RegionSectionWidgets\Schemas\RegionSectionWidgetForm;
use App\Filament\Resources\RegionSectionWidgets\Tables\RegionSectionWidgetsTable;
use App\Models\Pivots\RegionSectionWidget;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RegionSectionWidgetResource extends Resource
{
    protected static ?string $model = RegionSectionWidget::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return RegionSectionWidgetForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RegionSectionWidgetsTable::configure($table);
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
            'index' => ListRegionSectionWidgets::route('/'),
            'create' => CreateRegionSectionWidget::route('/create'),
            'edit' => EditRegionSectionWidget::route('/{record}/edit'),
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
