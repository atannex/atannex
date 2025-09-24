<?php

namespace App\Filament\Resources\Employees\Schemas;

use App\Enums\Status;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Employee Information')
                    ->description('Basic employee details and identification')
                    ->icon('heroicon-o-identification')
                    ->columns(2)
                    ->schema([
                        Select::make('user_id')
                            ->label('User Account')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->getOptionLabelFromRecordUsing(fn($record) => sprintf('%s (%s)', $record->name, $record->email))
                            ->placeholder('Select or search for user')
                            ->helperText('Link this employee to an existing user account')
                            ->suffixIcon('heroicon-m-user'),

                        TextInput::make('code')
                            ->label('Employee Number')
                            ->disabled()
                            ->unique(ignoreRecord: true)
                            ->maxLength(20)
                            ->placeholder('EMP001')
                            ->helperText('Unique employee identifier')
                            ->suffixIcon('heroicon-m-hashtag')
                            ->rules(['alpha_num']),
                    ]),

                Section::make('Employment Information')
                    ->description('Position, department, and employment information')
                    ->icon('heroicon-o-briefcase')
                    ->columns(2)
                    ->schema([
                        Select::make('departments')
                            ->label('Departments')
                            ->multiple()
                            ->relationship('departments', 'name')
                            ->preload()
                            ->searchable()
                            ->helperText('Select all departments this employee belongs to'),

                        Select::make('manager_id')
                            ->label('Direct Manager')
                            ->relationship('manager.user', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->getOptionLabelFromRecordUsing(fn($record) => $record->name)
                            ->placeholder('Select manager')
                            ->helperText("Employee's direct supervisor")
                            ->suffixIcon('heroicon-m-user-circle'),

                        Select::make('status')
                            ->label('Employment Status')
                            ->required()
                            ->options(Status::labels())
                            ->default('pending')
                            ->native(false)
                            ->helperText('Current employment status'),
                    ]),
            ])
            ->columns(1);
    }
}
