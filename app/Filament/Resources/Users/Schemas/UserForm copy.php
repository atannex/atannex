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

                // ==========================================
                // TOP ROW - Profile Image & Basic Info (Full Width)
                // ==========================================
               

                // ==========================================
                // SECOND ROW - Three Equal Columns
                // ==========================================

                // LEFT COLUMN - Location & Preferences
                Section::make('Location & Preferences')
                    ->icon('heroicon-o-map-pin')
                    ->iconColor('success')
                    ->description('Address and regional settings')
                    ->schema([
                        TextInput::make('address')
                            ->label('Street Address')
                            ->maxLength(255)
                            ->placeholder('123 Main Street, Apt 4B')
                            ->prefixIcon('heroicon-o-home')
                            ->columnSpanFull(),

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
                            ]),

                        Grid::make(2)
                            ->schema([
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
                            ->prefixIcon('heroicon-o-language')
                            ->columnSpanFull(),

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
                            ->prefixIcon('heroicon-o-clock')
                            ->columnSpanFull(),
                    ])
                    ->columnSpan(4)
                    ->collapsible(),

                // MIDDLE COLUMN - Security & Access
                Section::make('Security & Access')
                    ->icon('heroicon-o-shield-check')
                    ->iconColor('warning')
                    ->description('Authentication and account status')
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
                            ->live(debounce: 500)
                            ->columnSpanFull(),

                        TextInput::make('password_confirmation')
                            ->label('Confirm Password')
                            ->password()
                            ->revealable()
                            ->required(fn(Get $get): bool => filled($get('password')))
                            ->same('password')
                            ->placeholder('Confirm password')
                            ->prefixIcon('heroicon-o-lock-closed')
                            ->dehydrated(false)
                            ->columnSpanFull(),

                        Select::make('status')
                            ->label('Account Status')
                            ->options(Status::asArray())
                            ->required()
                            ->helperText('Controls user access to the platform')
                            ->prefixIcon('heroicon-o-signal')
                            ->live()
                            ->columnSpanFull(),

                        DateTimePicker::make('email_verified_at')
                            ->label('Email Verified At')
                            ->displayFormat('M j, Y \a\t g:i A')
                            ->helperText('When the user verified their email')
                            ->placeholder('Not yet verified')
                            ->prefixIcon('heroicon-o-check-badge')
                            ->columnSpanFull(),

                        Textarea::make('bio')
                            ->label('Biography')
                            ->rows(4)
                            ->maxLength(1000)
                            ->placeholder('Tell us about yourself...')
                            ->helperText('Public profile bio (max 1000 characters)')
                            ->columnSpanFull(),
                    ])
                    ->columnSpan(4)
                    ->collapsible(),

                // RIGHT COLUMN - Roles & Permissions
                Section::make('Roles & Permissions')
                    ->icon('heroicon-o-user-group')
                    ->iconColor('danger')
                    ->description('Assign roles and permissions')
                    ->schema([
                        Select::make('roles')
                            ->label('User Roles')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->placeholder('Select roles...')
                            ->helperText('Roles grant sets of permissions automatically')
                            ->prefixIcon('heroicon-o-shield-check')
                            ->live()
                            ->optionsLimit(20)
                            ->columnSpanFull(),

                        Select::make('permissions')
                            ->label('Additional Permissions')
                            ->relationship('permissions', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->placeholder('Select specific permissions...')
                            ->helperText('Extra permissions beyond role permissions')
                            ->prefixIcon('heroicon-o-key')
                            ->optionsLimit(50)
                            ->getSearchResultsUsing(
                                fn(string $search) => Permission::where('name', 'like', sprintf('%%%s%%', $search))
                                    ->limit(50)
                                    ->pluck('name', 'id')
                            )
                            ->columnSpanFull(),
                    ])
                    ->columnSpan(4)
                    ->collapsible(),

                // ==========================================
                // BOTTOM ROW - Status Information (Edit Mode Only)
                // ==========================================
                Section::make('Status Information')
                    ->icon('heroicon-o-information-circle')
                    ->iconColor('info')
                    ->description('View available status transitions for this account')
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
                    ->columnSpan(12)
                    ->visible(fn(?string $operation) => $operation === 'edit')
                    ->collapsible()
                    ->collapsed(),
            ]);
    }
}
