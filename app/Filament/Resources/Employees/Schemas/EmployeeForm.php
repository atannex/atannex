<?php

namespace App\Filament\Resources\Employees\Schemas;

use Carbon\Carbon;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

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

                        TextInput::make('employee_number')
                            ->label('Employee Number')
                            ->required()
                            ->disabled()
                            ->unique(ignoreRecord: true)
                            ->maxLength(20)
                            ->placeholder('EMP001')
                            ->helperText('Unique employee identifier')
                            ->suffixIcon('heroicon-m-hashtag')
                            ->rules(['alpha_num']),
                    ]),

                Section::make('Job Details')
                    ->description('Position, department, and employment information')
                    ->icon('heroicon-o-briefcase')
                    ->columns(2)
                    ->schema([
                        TextInput::make('job_title')
                            ->label('Job Title')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Software Developer')
                            ->helperText('Official job title')
                            ->suffixIcon('heroicon-m-briefcase'),

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

                        Select::make('employment_type')
                            ->label('Employment Type')
                            ->required()
                            ->options([
                                'full-time' => 'Full Time',
                                'part-time' => 'Part Time',
                                'contract' => 'Contract',
                                'intern' => 'Intern',
                                'temporary' => 'Temporary',
                            ])
                            ->default('full-time')
                            ->native(false)
                            ->helperText('Type of employment arrangement')
                            ->suffixIcon('heroicon-m-clock'),

                        DatePicker::make('hire_date')
                            ->label('Hire Date')
                            ->required()
                            ->maxDate(now())
                            ->displayFormat('M d, Y')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                if ($state && $get('employment_type') !== 'intern') {
                                    $probationEnd = Carbon::parse($state)->addDays(90);
                                    $set('probation_end_date', $probationEnd->format('Y-m-d'));
                                }
                            })
                            ->helperText("Employee's start date")
                            ->suffixIcon('heroicon-m-calendar'),


                        Select::make('status')
                            ->label('Employment Status')
                            ->required()
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                                'on-leave' => 'On Leave',
                                'terminated' => 'Terminated',
                                'probation' => 'Probation',
                            ])
                            ->default('probation')
                            ->native(false)
                            ->helperText('Current employment status'),
                    ]),

                Section::make('Contact Information')
                    ->description('Employee contact details')
                    ->icon('heroicon-o-phone')
                    ->columns(2)
                    ->collapsed()
                    ->schema([
                        TextInput::make('phone')
                            ->label('Phone Number')
                            ->tel()
                            ->nullable()
                            ->maxLength(20)
                            ->placeholder('+1 (555) 123-4567')
                            ->helperText('Primary contact number')
                            ->suffixIcon('heroicon-m-phone'),

                        TextInput::make('emergency_contact_name')
                            ->label('Emergency Contact Name')
                            ->nullable()
                            ->maxLength(255)
                            ->placeholder('Jane Doe')
                            ->helperText('Emergency contact person'),

                        TextInput::make('emergency_contact_phone')
                            ->label('Emergency Contact Phone')
                            ->tel()
                            ->nullable()
                            ->maxLength(20)
                            ->placeholder('+1 (555) 987-6543')
                            ->helperText('Emergency contact number')
                            ->suffixIcon('heroicon-m-phone'),

                        TextInput::make('emergency_contact_relationship')
                            ->label('Emergency Contact Relationship')
                            ->nullable()
                            ->maxLength(100)
                            ->placeholder('Spouse, Parent, Sibling, etc.')
                            ->helperText('Relationship to emergency contact'),

                        Textarea::make('address')
                            ->label('Home Address')
                            ->nullable()
                            ->rows(3)
                            ->maxLength(500)
                            ->placeholder('123 Main Street, City, State, ZIP')
                            ->helperText("Employee's home address")
                            ->columnSpanFull(),
                    ]),

                Section::make('Additional Information')
                    ->description('Notes, documents, and other details')
                    ->icon('heroicon-o-document-text')
                    ->collapsed()
                    ->schema([
                        FileUpload::make('profile_photo')
                            ->label('Profile Photo')
                            ->image()
                            ->maxSize(2048)
                            ->directory('employee-photos')
                            ->visibility('private')
                            ->helperText('Optional profile photo (max 2MB)')
                            ->columnSpan(1),

                        Textarea::make('notes')
                            ->label('Notes')
                            ->nullable()
                            ->rows(4)
                            ->maxLength(1000)
                            ->placeholder('Additional notes about the employee...')
                            ->helperText('Internal notes (max 1000 characters)')
                            ->columnSpan(2),
                    ]),
            ])
            ->columns(1);
    }
}
