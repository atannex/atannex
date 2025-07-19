<?php

namespace App\Filament\Resources\DocumentModules;

use App\Filament\Resources\DocumentModules\Pages\CreateDocumentModule;
use App\Filament\Resources\DocumentModules\Pages\EditDocumentModule;
use App\Filament\Resources\DocumentModules\Pages\ListDocumentModules;
use App\Filament\Resources\DocumentModules\Schemas\DocumentModuleForm;
use App\Filament\Resources\DocumentModules\Tables\DocumentModulesTable;
use App\Models\Modules\DocumentModule;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DocumentModuleResource extends Resource
{
    protected static ?string $model = DocumentModule::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return DocumentModuleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DocumentModulesTable::configure($table);
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
            'index' => ListDocumentModules::route('/'),
            'create' => CreateDocumentModule::route('/create'),
            'edit' => EditDocumentModule::route('/{record}/edit'),
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
