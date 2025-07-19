<?php

namespace App\Filament\Resources\EmployeeDepartments\Schemas;

use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class EmployeeDepartmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('employee_id')
                    ->relationship('employee', 'id')
                    ->required(),
                Select::make('department_id')
                    ->relationship('department', 'name')
                    ->required(),
            ]);
    }
}
