<?php

namespace App\Filament\Resources\PostModules;

use App\Filament\Resources\PostModules\Pages\CreatePostModule;
use App\Filament\Resources\PostModules\Pages\EditPostModule;
use App\Filament\Resources\PostModules\Pages\ListPostModules;
use App\Filament\Resources\PostModules\Schemas\PostModuleForm;
use App\Filament\Resources\PostModules\Tables\PostModulesTable;
use App\Models\Modules\PostModule;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostModuleResource extends Resource
{
    protected static ?string $model = PostModule::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return PostModuleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PostModulesTable::configure($table);
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
            'index' => ListPostModules::route('/'),
            'create' => CreatePostModule::route('/create'),
            'edit' => EditPostModule::route('/{record}/edit'),
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
