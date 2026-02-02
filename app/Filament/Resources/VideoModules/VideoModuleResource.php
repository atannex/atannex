<?php

namespace App\Filament\Resources\VideoModules;

use App\Filament\Resources\VideoModules\Pages\CreateVideoModule;
use App\Filament\Resources\VideoModules\Pages\EditVideoModule;
use App\Filament\Resources\VideoModules\Pages\ListVideoModules;
use App\Filament\Resources\VideoModules\Schemas\VideoModuleForm;
use App\Filament\Resources\VideoModules\Tables\VideoModulesTable;
use App\Models\Posts\VideoModule;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VideoModuleResource extends Resource
{
    protected static ?string $model = VideoModule::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return VideoModuleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VideoModulesTable::configure($table);
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
            'index' => ListVideoModules::route('/'),
            'create' => CreateVideoModule::route('/create'),
            'edit' => EditVideoModule::route('/{record}/edit'),
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
