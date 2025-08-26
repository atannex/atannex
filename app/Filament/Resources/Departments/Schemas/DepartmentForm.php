<?php

namespace App\Filament\Resources\Departments\Schemas;

use Illuminate\Support\Str;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class DepartmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Department Information')
                    ->description('Basic information about the department')
                    ->icon('heroicon-o-building-office-2')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Department Name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                self::autoGenerateSlug($get, $set, $state);
                            })
                            ->placeholder('Enter department name')
                            ->helperText('The official name of the department'),


                        TextInput::make('slug')
                            ->label('URL Slug')
                            ->required()
                            ->maxLength(255)
                            ->disabled()
                            ->unique(ignoreRecord: true)
                            ->rules(['alpha_dash'])
                            ->placeholder('auto-generated-slug')
                            ->helperText('URL-friendly version (auto-generated from name)')
                            ->suffixIcon('heroicon-m-link'),

                        TextInput::make('department_code')
                            ->label('Department Code')
                            ->required()
                            ->maxLength(20)
                            ->disabled()
                            ->unique(ignoreRecord: true)
                            // ->rules(['alpha_num'])
                            ->placeholder('DEPT001')
                            ->helperText('Unique identifier for the department')
                            ->suffixIcon('heroicon-m-hashtag')
                            ->columnSpan(1),

                        Select::make('status')
                            ->label('Status')
                            ->required()
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                                'draft' => 'Draft',
                                'suspended' => 'Suspended',
                            ])
                            ->default('draft')
                            ->native(false)
                            ->helperText('Current operational status')
                            ->suffixIcon('heroicon-m-signal'),

                        Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->maxLength(1000)
                            ->placeholder('Brief description of the department\'s purpose and responsibilities...')
                            ->helperText('Optional description (max 1000 characters)')
                            ->columnSpanFull(),
                    ]),

                Section::make('Organizational Structure')
                    ->description('Department hierarchy and management')
                    ->icon('heroicon-o-user-group')
                    ->columns(2)
                    ->schema([
                        Select::make('parent_id')
                            ->label('Parent Department')
                            ->relationship('parent', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->placeholder('Select parent department (optional)')
                            ->helperText('Leave empty if this is a root department')
                            ->suffixIcon('heroicon-m-building-office'),

                        Select::make('manager_id')
                            ->label('Department Manager')
                            ->relationship('manager.user', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->getOptionLabelFromRecordUsing(fn($record) => "{$record->name} ({$record->email})")
                            ->placeholder('Select department manager (optional)')
                            ->helperText('Assign a manager to oversee this department')
                            ->suffixIcon('heroicon-m-user'),

                        TextColumn::make('hierarchy_info')
                            ->label('Hierarchy Information')
                            ->getStateUsing(function (Get $get) {
                                return $get('parent_id')
                                    ? 'This department will be a sub-department.'
                                    : 'This will be a root-level department.';
                            })
                            ->columnSpanFull(),
                    ]),

                Section::make('Contact Information')
                    ->description('Department contact details')
                    ->icon('heroicon-o-phone')
                    ->columns(2)
                    ->schema([
                        TextInput::make('phone')
                            ->label('Phone Number')
                            ->tel()
                            ->nullable()
                            ->maxLength(20)
                            ->placeholder('+1 (555) 123-4567')
                            ->helperText('Primary contact number for the department')
                            ->suffixIcon('heroicon-m-phone'),

                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->nullable()
                            ->maxLength(255)
                            ->placeholder('department@company.com')
                            ->helperText('Official department email address')
                            ->suffixIcon('heroicon-m-envelope'),
                    ]),
            ])
            ->columns(1);
    }

    /**
     * Auto-generate slug from name if slug is empty or matches previous name.
     */
    public static function autoGenerateSlug(Get $get, Set $set, ?string $state): void
    {
        if (! $get('slug') || $get('slug') === Str::slug($get('name'))) {
            $set('slug', Str::slug($state ?? ''));
        }
    }
}
