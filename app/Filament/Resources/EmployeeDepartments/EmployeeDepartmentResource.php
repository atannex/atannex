<?php

namespace App\Filament\Resources\EmployeeDepartments;

use App\Filament\Resources\EmployeeDepartments\Pages\CreateEmployeeDepartment;
use App\Filament\Resources\EmployeeDepartments\Pages\EditEmployeeDepartment;
use App\Filament\Resources\EmployeeDepartments\Pages\ListEmployeeDepartments;
use App\Filament\Resources\EmployeeDepartments\Schemas\EmployeeDepartmentForm;
use App\Filament\Resources\EmployeeDepartments\Tables\EmployeeDepartmentsTable;
use App\Models\Pivots\EmployeeDepartment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EmployeeDepartmentResource extends Resource
{
    protected static ?string $model = EmployeeDepartment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return EmployeeDepartmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmployeeDepartmentsTable::configure($table);
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
            'index' => ListEmployeeDepartments::route('/'),
            'create' => CreateEmployeeDepartment::route('/create'),
            'edit' => EditEmployeeDepartment::route('/{record}/edit'),
        ];
    }
}
