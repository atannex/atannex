<?php

namespace App\Filament\Resources\Employees\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                TextInput::make('employee_number')
                    ->required(),
                TextInput::make('job_title')
                    ->default(null),
                DatePicker::make('hire_date'),
                TextInput::make('employment_type')
                    ->required()
                    ->default('full_time'),
                TextInput::make('status')
                    ->required()
                    ->default('restricted'),
            ]);
    }
}
