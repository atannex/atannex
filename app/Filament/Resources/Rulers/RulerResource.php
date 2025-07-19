<?php

namespace App\Filament\Resources\Rulers;

use App\Filament\Resources\Rulers\Pages\CreateRuler;
use App\Filament\Resources\Rulers\Pages\EditRuler;
use App\Filament\Resources\Rulers\Pages\ListRulers;
use App\Filament\Resources\Rulers\Schemas\RulerForm;
use App\Filament\Resources\Rulers\Tables\RulersTable;
use App\Models\Regions\Ruler;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RulerResource extends Resource
{
    protected static ?string $model = Ruler::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return RulerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RulersTable::configure($table);
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
            'index' => ListRulers::route('/'),
            'create' => CreateRuler::route('/create'),
            'edit' => EditRuler::route('/{record}/edit'),
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
