<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Schema;

use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Validation\Rules\Password;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('User Management')
                    ->tabs([

                        Tab::make('Basic Information')
                            ->icon('heroicon-o-user')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        Section::make('Personal Details')
                                            ->icon('heroicon-o-identification')
                                            ->description('Basic user information and contact details')
                                            ->schema([
                                                TextInput::make('name')
                                                    ->label('Full Name')
                                                    ->required()
                                                    ->maxLength(255)
                                                    ->autocomplete('name')
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(
                                                        fn(Set $set, ?string $state) =>
                                                        $set('display_name', $state)
                                                    )
                                                    ->placeholder('Enter full name')
                                                    ->helperText('This will be displayed across the platform'),

                                                TextInput::make('display_name')
                                                    ->label('Display Name')
                                                    ->maxLength(255)
                                                    ->placeholder('How should we display this user?')
                                                    ->helperText('Optional: Leave blank to use full name'),

                                                TextInput::make('email')
                                                    ->label('Email Address')
                                                    ->email()
                                                    ->required()
                                                    ->unique(ignoreRecord: true)
                                                    ->maxLength(255)
                                                    ->autocomplete('email')
                                                    ->prefixIcon('heroicon-o-envelope')
                                                    ->placeholder('user@example.com')
                                                    ->helperText('This will be used for login and notifications'),

                                                TextInput::make('phone')
                                                    ->label('Phone Number')
                                                    ->tel()
                                                    ->maxLength(20)
                                                    ->prefixIcon('heroicon-o-phone')
                                                    ->placeholder('+1 (555) 123-4567')
                                                    ->helperText('Include country code for international numbers'),
                                            ])
                                            ->columns(1),

                                        Section::make('Demographics')
                                            ->icon('heroicon-o-user-group')
                                            ->description('Optional demographic information')
                                            ->schema([
                                                DatePicker::make('date_of_birth')
                                                    ->label('Date of Birth')
                                                    ->maxDate(now()->subYears(13))
                                                    ->displayFormat('M j, Y')
                                                    ->placeholder('Select date of birth')
                                                    ->helperText('Must be at least 13 years old'),

                                                Select::make('gender')
                                                    ->label('Gender')
                                                    ->options([
                                                        'male' => 'Male',
                                                        'female' => 'Female',
                                                        'non-binary' => 'Non-binary',
                                                        'other' => 'Other',
                                                        'prefer-not-to-say' => 'Prefer not to say',
                                                    ])
                                                    ->default('prefer-not-to-say')
                                                    ->placeholder('Select gender')
                                                    ->helperText('This information is optional and private'),

                                                Select::make('timezone')
                                                    ->label('Timezone')
                                                    ->options([
                                                        'America/New_York' => 'Eastern Time (EST/EDT)',
                                                        'America/Chicago' => 'Central Time (CST/CDT)',
                                                        'America/Denver' => 'Mountain Time (MST/MDT)',
                                                        'America/Los_Angeles' => 'Pacific Time (PST/PDT)',
                                                        'Europe/London' => 'Greenwich Mean Time (GMT)',
                                                        'Europe/Paris' => 'Central European Time (CET)',
                                                        'Asia/Tokyo' => 'Japan Standard Time (JST)',
                                                        'Australia/Sydney' => 'Australian Eastern Time (AET)',
                                                    ])
                                                    ->searchable()
                                                    ->placeholder('Select timezone')
                                                    ->helperText('Used for displaying dates and times'),
                                            ])
                                            ->columns(1),
                                    ]),

                                Section::make('Profile Image')
                                    ->icon('heroicon-o-camera')
                                    ->description('Upload a profile picture for this user')
                                    ->schema([
                                        FileUpload::make('image')
                                            ->label('Profile Picture')
                                            ->disk('public')
                                            ->directory('profiles')
                                            ->visibility('public')
                                            ->image()
                                            ->imageEditor()
                                            ->imageEditorMode(2)
                                            ->imageEditorAspectRatios([
                                                '1:1' => 'Square (Recommended)',
                                                '4:3' => 'Standard (4:3)',
                                                '16:9' => 'Widescreen (16:9)',
                                            ])
                                            ->imageResizeMode('cover')
                                            ->imageCropAspectRatio('1:1')
                                            ->imageResizeTargetWidth('400')
                                            ->imageResizeTargetHeight('400')
                                            ->maxSize(2048)
                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                            ->helperText('Recommended: Square image, 400x400px, max 2MB')
                                            ->imagePreviewHeight('150')
                                            ->uploadingMessage('Uploading profile picture...')
                                            ->panelLayout('integrated')
                                            ->removeUploadedFileButtonPosition('top-right'),
                                    ])
                                    ->collapsible()
                                    ->collapsed(fn(?string $operation) => $operation === 'create')
                                    ->columnSpanFull(),
                            ]),

                        Tab::make('Security')
                            ->icon('heroicon-o-lock-closed')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        Section::make('Authentication')
                                            ->icon('heroicon-o-key')
                                            ->description('Login credentials and security settings')
                                            ->schema([
                                                TextInput::make('password')
                                                    ->label('Password')
                                                    ->password()
                                                    ->revealable()
                                                    ->required(fn(string $operation): bool => $operation === 'create')
                                                    ->confirmed()
                                                    ->rule(Password::default())
                                                    ->autocomplete('new-password')
                                                    ->placeholder('Enter secure password')
                                                    ->helperText('Must be at least 8 characters with mixed case, numbers, and symbols')
                                                    ->dehydrateStateUsing(fn($state) => Hash::make($state))
                                                    ->dehydrated(fn($state) => filled($state))
                                                    ->live(debounce: 500),

                                                TextInput::make('password_confirmation')
                                                    ->label('Confirm Password')
                                                    ->password()
                                                    ->revealable()
                                                    ->required(fn(Get $get): bool => filled($get('password')))
                                                    ->same('password')
                                                    ->placeholder('Confirm password')
                                                    ->dehydrated(false),
                                            ]),

                                        Section::make('Account Status')
                                            ->icon('heroicon-o-shield-check')
                                            ->description('Control account access and restrictions')
                                            ->schema([
                                                Select::make('status')
                                                    ->label('Account Status')
                                                    ->options([
                                                        'active' => 'Active',
                                                        'inactive' => 'Inactive',
                                                        'suspended' => 'Suspended',
                                                        'banned' => 'Banned',
                                                        'pending' => 'Pending Approval',
                                                        'restricted' => 'Restricted Access',
                                                    ])
                                                    ->default('pending')
                                                    ->required()
                                                    ->helperText('Controls user access to the platform')
                                                    ->live(),

                                                DateTimePicker::make('email_verified_at')
                                                    ->label('Email Verified At')
                                                    ->displayFormat('M j, Y \a\t g:i A')
                                                    ->helperText('When the user verified their email address')
                                                    ->placeholder('Not yet verified'),
                                            ]),
                                    ]),
                            ]),

                        Tab::make('Permissions')
                            ->icon('heroicon-o-shield-exclamation')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        Section::make('Role Assignment')
                                            ->icon('heroicon-o-user-group')
                                            ->description('Assign roles to grant sets of permissions')
                                            ->schema([
                                                Select::make('roles')
                                                    ->label('User Roles')
                                                    ->relationship('roles', 'name')
                                                    ->multiple()
                                                    ->searchable()
                                                    ->preload()
                                                    ->placeholder('Select roles...')
                                                    ->helperText('Roles automatically grant associated permissions')
                                                    ->live()
                                                    ->optionsLimit(20),
                                            ]),

                                        Section::make('Direct Permissions')
                                            ->icon('heroicon-o-key')
                                            ->description('Grant specific permissions directly')
                                            ->schema([
                                                Select::make('permissions')
                                                    ->label('Additional Permissions')
                                                    ->relationship('permissions', 'name')
                                                    ->multiple()
                                                    ->searchable()
                                                    ->preload()
                                                    ->placeholder('Select permissions...')
                                                    ->helperText('These permissions are in addition to role permissions')
                                                    ->optionsLimit(50)
                                                    ->getSearchResultsUsing(
                                                        fn(string $search) =>
                                                        \Spatie\Permission\Models\Permission::where('name', 'like', "%{$search}%")
                                                            ->limit(50)
                                                            ->pluck('name', 'id')
                                                    ),
                                            ]),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull()
                    ->persistTabInQueryString(),
            ]);
    }
}
