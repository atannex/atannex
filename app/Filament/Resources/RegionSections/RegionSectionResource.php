<?php

namespace App\Filament\Resources\RegionSections;

use App\Filament\Resources\RegionSections\Pages\CreateRegionSection;
use App\Filament\Resources\RegionSections\Pages\EditRegionSection;
use App\Filament\Resources\RegionSections\Pages\ListRegionSections;
use App\Filament\Resources\RegionSections\Schemas\RegionSectionForm;
use App\Filament\Resources\RegionSections\Tables\RegionSectionsTable;
use App\Models\Pivots\RegionSection;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RegionSectionResource extends Resource
{
    protected static ?string $model = RegionSection::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return RegionSectionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RegionSectionsTable::configure($table);
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
            'index' => ListRegionSections::route('/'),
            'create' => CreateRegionSection::route('/create'),
            'edit' => EditRegionSection::route('/{record}/edit'),
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
