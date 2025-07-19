<?php

namespace App\Filament\Resources\Widgets;

use UnitEnum;
use BackedEnum;
use Filament\Tables\Table;
use App\Models\Pages\Widget;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\Widgets\Pages\EditWidget;
use App\Filament\Resources\Widgets\Pages\ListWidgets;
use App\Filament\Resources\Widgets\Pages\CreateWidget;
use App\Filament\Resources\Widgets\Schemas\WidgetForm;
use App\Filament\Resources\Widgets\Tables\WidgetsTable;

class WidgetResource extends Resource
{
    protected static ?string $model = Widget::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | UnitEnum | null $navigationGroup = 'Settings';

    public static function form(Schema $schema): Schema
    {
        return WidgetForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WidgetsTable::configure($table);
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
            'index' => ListWidgets::route('/'),
            'create' => CreateWidget::route('/create'),
            'edit' => EditWidget::route('/{record}/edit'),
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
