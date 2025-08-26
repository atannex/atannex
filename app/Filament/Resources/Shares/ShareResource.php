<?php

namespace App\Filament\Resources\Shares;

use App\Filament\Resources\Shares\Pages\CreateShare;
use App\Filament\Resources\Shares\Pages\EditShare;
use App\Filament\Resources\Shares\Pages\ListShares;
use App\Filament\Resources\Shares\Schemas\ShareForm;
use App\Filament\Resources\Shares\Tables\SharesTable;
use App\Models\Interactions\Share;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShareResource extends Resource
{
    protected static ?string $model = Share::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ShareForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SharesTable::configure($table);
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
            'index' => ListShares::route('/'),
            'create' => CreateShare::route('/create'),
            'edit' => EditShare::route('/{record}/edit'),
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
