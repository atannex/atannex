<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\Gender;
use App\Enums\Status;
use Illuminate\Support\Str;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Spatie\Permission\Models\Permission;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Illuminate\Validation\Rules\Password;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->components([
                Group::make()
                    ->schema([
                        Section::make('User Profile')
                            ->description('Basic user information and profile picture')
                            ->icon('heroicon-o-identification')
                            ->iconColor('primary')
                            ->schema([
                                Grid::make(12)
                                    ->schema([
                                        FileUpload::make('image')
                                            ->label('Profile Picture')
                                            ->disk('public')
                                            ->directory(fn($record) => $record?->dir() ?? 'users')
                                            ->visibility('public')
                                            ->image()
                                            ->imageEditor()
                                            ->imageEditorMode(2)
                                            ->imageEditorAspectRatios([
                                                '1:1' => 'Square (Recommended)',
                                            ])
                                            ->imageResizeMode('cover')
                                            ->imageCropAspectRatio('1:1')
                                            ->imageResizeTargetWidth('400')
                                            ->imageResizeTargetHeight('400')
                                            ->maxSize(2048)
                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                            ->helperText('Recommended: 400x400px, max 2MB')
                                            ->imagePreviewHeight('200')
                                            ->uploadingMessage('Uploading...')
                                            ->panelLayout('integrated')
                                            ->removeUploadedFileButtonPosition('top-right')
                                            ->avatar()
                                            ->alignCenter()
                                            ->columnSpan(3),

                                        Group::make()
                                            ->schema([
                                                TextInput::make('name')
                                                    ->label('Full Name')
                                                    ->required()
                                                    ->maxLength(255)
                                                    ->autocomplete('name')
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(function (Set $set, ?string $state) {
                                                        $set('slug', Str::slug($state));
                                                    })
                                                    ->placeholder('Enter full name')
                                                    ->prefixIcon('heroicon-o-user')
                                                    ->columnSpanFull(),

                                                TextInput::make('slug')
                                                    ->label('URL Slug')
                                                    ->maxLength(255)
                                                    ->unique(ignoreRecord: true)
                                                    ->placeholder('auto-generated-slug')
                                                    ->prefixIcon('heroicon-o-link')
                                                    ->disabled()
                                                    ->dehydrated()
                                                    ->visible(fn(?string $operation) => $operation === 'edit')
                                                    ->columnSpanFull(),

                                                Grid::make(2)
                                                    ->schema([
                                                        TextInput::make('email')
                                                            ->label('Email Address')
                                                            ->email()
                                                            ->required()
                                                            ->unique(ignoreRecord: true)
                                                            ->maxLength(255)
                                                            ->autocomplete('email')
                                                            ->prefixIcon('heroicon-o-envelope')
                                                            ->placeholder('user@example.com'),

                                                        TextInput::make('phone')
                                                            ->label('Phone Number')
                                                            ->tel()
                                                            ->maxLength(20)
                                                            ->prefixIcon('heroicon-o-phone')
                                                            ->placeholder('+1 (555) 123-4567'),
                                                    ]),

                                                Grid::make(2)
                                                    ->schema([
                                                        DatePicker::make('date_of_birth')
                                                            ->label('Date of Birth')
                                                            ->maxDate(now()->subYears(13))
                                                            ->displayFormat('M j, Y')
                                                            ->placeholder('Select date')
                                                            ->prefixIcon('heroicon-o-cake'),

                                                        Select::make('gender')
                                                            ->label('Gender')
                                                            ->searchable()
                                                            ->preload()
                                                            ->options(Gender::asSelectArray())
                                                            ->placeholder('Select gender')
                                                            ->prefixIcon('heroicon-o-user-circle'),
                                                    ]),
                                            ])
                                            ->columnSpan(9),
                                    ]),
                            ])
                            ->columnSpan(12),
                    ])
                    ->columnSpan(12),

                Group::make()
                    ->schema([
                        Section::make('Location & Preferences')
                            ->icon('heroicon-o-map-pin')
                            ->iconColor('success')
                            ->description('Address and regional settings')
                            ->schema([
                                Grid::make(1)
                                    ->schema([
                                        TextInput::make('address')
                                            ->label('Street Address')
                                            ->maxLength(255)
                                            ->placeholder('123 Main Street, Apt 4B')
                                            ->prefixIcon('heroicon-o-home'),
                                    ]),
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('city')
                                            ->label('City')
                                            ->maxLength(100)
                                            ->placeholder('New York')
                                            ->prefixIcon('heroicon-o-building-office-2'),

                                        TextInput::make('state')
                                            ->label('State/Province')
                                            ->maxLength(100)
                                            ->placeholder('NY')
                                            ->prefixIcon('heroicon-o-map'),

                                        TextInput::make('country')
                                            ->label('Country')
                                            ->maxLength(100)
                                            ->placeholder('United States')
                                            ->default('United States')
                                            ->prefixIcon('heroicon-o-globe-alt'),

                                        TextInput::make('zip_code')
                                            ->label('ZIP/Postal Code')
                                            ->maxLength(20)
                                            ->placeholder('10001')
                                            ->prefixIcon('heroicon-o-hashtag'),
                                    ]),

                                Grid::make(1)
                                    ->schema([
                                        Select::make('locale')
                                            ->label('Language')
                                            ->options([
                                                'en' => '🇺🇸 English',
                                                'es' => '🇪🇸 Spanish',
                                                'fr' => '🇫🇷 French',
                                                'de' => '🇩🇪 German',
                                                'it' => '🇮🇹 Italian',
                                                'pt' => '🇵🇹 Portuguese',
                                                'ja' => '🇯🇵 Japanese',
                                                'zh' => '🇨🇳 Chinese',
                                                'ar' => '🇸🇦 Arabic',
                                                'ru' => '🇷🇺 Russian',
                                            ])
                                            ->default('en')
                                            ->searchable()
                                            ->placeholder('Select language')
                                            ->prefixIcon('heroicon-o-language'),

                                        Select::make('timezone')
                                            ->label('Timezone')
                                            ->options([
                                                'America/New_York' => 'Eastern Time (EST/EDT)',
                                                'America/Chicago' => 'Central Time (CST/CDT)',
                                                'America/Denver' => 'Mountain Time (MST/MDT)',
                                                'America/Los_Angeles' => 'Pacific Time (PST/PDT)',
                                                'America/Phoenix' => 'Arizona (MST)',
                                                'America/Anchorage' => 'Alaska (AKST/AKDT)',
                                                'Pacific/Honolulu' => 'Hawaii (HST)',
                                                'Europe/London' => 'Greenwich Mean Time (GMT)',
                                                'Europe/Paris' => 'Central European Time (CET)',
                                                'Europe/Berlin' => 'Central European Time (CET)',
                                                'Europe/Rome' => 'Central European Time (CET)',
                                                'Europe/Madrid' => 'Central European Time (CET)',
                                                'Asia/Dubai' => 'Gulf Standard Time (GST)',
                                                'Asia/Tokyo' => 'Japan Standard Time (JST)',
                                                'Asia/Shanghai' => 'China Standard Time (CST)',
                                                'Asia/Hong_Kong' => 'Hong Kong Time (HKT)',
                                                'Asia/Singapore' => 'Singapore Time (SGT)',
                                                'Australia/Sydney' => 'Australian Eastern Time (AET)',
                                                'Australia/Melbourne' => 'Australian Eastern Time (AET)',
                                                'Pacific/Auckland' => 'New Zealand Time (NZST)',
                                            ])
                                            ->searchable()
                                            ->placeholder('Select timezone')
                                            ->prefixIcon('heroicon-o-clock'),
                                    ]),
                            ])
                            ->collapsible(),
                    ])
                    ->columnSpan(4),

                Group::make()
                    ->schema([
                        Section::make('Security & Access')
                            ->icon('heroicon-o-shield-check')
                            ->iconColor('warning')
                            ->description('Authentication and account status')
                            ->schema([
                                Grid::make(1)
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
                                            ->helperText('Min 8 characters with mixed case, numbers & symbols')
                                            ->prefixIcon('heroicon-o-key')
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
                                            ->prefixIcon('heroicon-o-lock-closed')
                                            ->dehydrated(false),
                                    ]),

                                Grid::make(1)
                                    ->schema([
                                        Select::make('status')
                                            ->label('Status')
                                            ->options(Status::asSelectArray())
                                            ->preload()
                                            ->searchable()
                                            ->required()
                                            ->helperText('Controls user access')
                                            ->prefixIcon('heroicon-o-signal')
                                            ->live(),

                                        DateTimePicker::make('email_verified_at')
                                            ->label('Email Verified At')
                                            ->displayFormat('M j, Y g:i A')
                                            ->helperText('Email verification time')
                                            ->placeholder('Not verified')
                                            ->prefixIcon('heroicon-o-check-badge'),
                                    ]),

                                Grid::make(1)
                                    ->schema([
                                        Textarea::make('bio')
                                            ->label('Biography')
                                            ->rows(4)
                                            ->maxLength(1000)
                                            ->placeholder('Tell us about yourself...')
                                            ->helperText('Public profile bio (max 1000 characters)'),
                                    ]),
                            ])
                            ->collapsible(),
                    ])
                    ->columnSpan(4),

                Group::make()
                    ->schema([
                        Section::make('Roles & Permissions')
                            ->icon('heroicon-o-user-group')
                            ->iconColor('danger')
                            ->description('Assign roles and permissions')
                            ->schema([
                                Grid::make(1)
                                    ->schema([
                                        Select::make('roles')
                                            ->label('User Roles')
                                            ->relationship('roles', 'name')
                                            ->multiple()
                                            ->searchable()
                                            ->preload()
                                            ->placeholder('Select roles...')
                                            ->helperText('Roles grant sets of permissions')
                                            ->prefixIcon('heroicon-o-shield-check')
                                            ->live()
                                            ->optionsLimit(20),

                                        Select::make('permissions')
                                            ->label('Additional Permissions')
                                            ->relationship('permissions', 'name')
                                            ->multiple()
                                            ->searchable()
                                            ->preload()
                                            ->placeholder('Select permissions...')
                                            ->helperText('Extra permissions beyond roles')
                                            ->prefixIcon('heroicon-o-key')
                                            ->optionsLimit(50)
                                            ->getSearchResultsUsing(
                                                fn(string $search) => Permission::where('name', 'like', sprintf('%%%s%%', $search))
                                                    ->limit(50)
                                                    ->pluck('name', 'id')
                                            ),
                                    ]),
                            ])
                            ->collapsible(),

                        Section::make('Status Information')
                            ->icon('heroicon-o-information-circle')
                            ->iconColor('info')
                            ->description('View available status transitions')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextEntry::make('status_info')
                                            ->label('Current Status')
                                            ->state(
                                                fn($record): string => $record && $record->status
                                                    ? Status::describe($record->status)
                                                    : 'N/A'
                                            )
                                            ->badge()
                                            ->color(fn($record): string => match ($record?->status ?? null) {
                                                'active' => 'success',
                                                'inactive' => 'warning',
                                                'suspended' => 'danger',
                                                default => 'gray',
                                            }),

                                        TextEntry::make('allowed_transitions')
                                            ->label('Allowed Transitions')
                                            ->state(function ($record): string {
                                                if (!$record || !$record->status) {
                                                    return 'N/A';
                                                }

                                                $transitions = Status::allowedTransitions($record->status);

                                                return $transitions
                                                    ? implode(', ', array_map(fn($s) => Status::describe($s), $transitions))
                                                    : 'None available';
                                            })
                                            ->badge()
                                            ->color('gray'),
                                    ]),
                            ])
                            ->visible(fn(?string $operation) => $operation === 'edit')
                            ->collapsible()
                            ->collapsed(),
                    ])
                    ->columnSpan(4),
            ]);
    }
}
