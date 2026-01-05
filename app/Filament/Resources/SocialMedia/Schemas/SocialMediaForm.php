<?php

namespace App\Filament\Resources\SocialMedia\Schemas;

use App\Enums\Flag;
use App\Enums\Icon;
use App\Models\Regions\Employee;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class SocialMediaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::mainContentGroup(),
                self::ownershipGroup(),
            ])
            ->columns(3);
    }

    private static function mainContentGroup(): Group
    {
        return Group::make([
            self::basicInformationSection(),
            self::platformSettingsSection(),
        ])->columnSpan(2);
    }

    private static function basicInformationSection(): Section
    {
        return Section::make('Basic Information')
            ->description('Configure your social media account details')
            ->icon('heroicon-o-identification')
            ->schema([
                Grid::make(2)->schema([
                    self::accountLabelField(),
                    self::urlField(),
                ]),
            ]);
    }

    private static function platformSettingsSection(): Section
    {
        return Section::make('Platform & Settings')
            ->description('Select platform and configure account settings')
            ->icon('heroicon-o-cog-6-tooth')
            ->schema([
                Grid::make(3)->schema([
                    self::platformField(),
                    self::statusFlagField(),
                    self::orderField(),
                ]),
            ]);
    }

    private static function ownershipGroup(): Group
    {
        return Group::make([
            self::ownershipSection(),
        ])->columnSpan(1);
    }

    private static function ownershipSection(): Section
    {
        return Section::make('Account Ownership')
            ->description('Define who owns and manages this account')
            ->icon('heroicon-o-user-group')
            ->schema([
                Grid::make(1)->schema([
                    self::globalToggleField(),
                    self::ownerTypeField(),
                    self::ownerIdField(),
                ]),
            ]);
    }

    private static function accountLabelField(): TextInput
    {
        return TextInput::make('label')
            ->label('Account Label')
            ->placeholder('e.g., Main Facebook Page')
            ->helperText('A descriptive name for this social media account')
            ->required()
            ->maxLength(255);
    }

    private static function urlField(): TextInput
    {
        return TextInput::make('url')
            ->label('Social Media URL')
            ->placeholder('https://facebook.com/your-page')
            ->helperText('The complete URL to your social media profile')
            ->url()
            ->required()
            ->maxLength(255);
    }

    private static function platformField(): Component
    {
        return Select::make('platform')
            ->options(Icon::asSelectArray())
            ->default(Icon::FACEBOOK)
            ->label('Social Platform')
            ->preload()
            ->searchable()
            ->helperText('Choose the social media platform')
            ->reactive()
            ->rules(fn ($get, ?Model $record) => self::platformValidationRules($get, $record))
            ->validationMessages([
                'unique' => 'This platform is already registered for the selected owner/type combination.',
            ]);
    }

    private static function statusFlagField(): Component
    {
        return Select::make('flag')
            ->preload()
            ->searchable()
            ->options(Flag::asSelectArray())
            ->default(Flag::DRAFT)
            ->label('Status Flag')
            ->helperText('Current status of this account');
    }

    private static function orderField(): TextInput
    {
        return TextInput::make('order')
            ->label('Display Order')
            ->helperText('Order for displaying this account')
            ->required()
            ->numeric()
            ->default(0)
            ->minValue(0);
    }

    private static function globalToggleField(): Toggle
    {
        return Toggle::make('is_global')
            ->label('Global Icon')
            ->live()
            ->afterStateUpdated(
                fn (Set $set, $state) => $state
                    ? [$set('owner_id', null), $set('owner_type', null)]
                    : null
            )
            ->default(false);
    }

    private static function ownerTypeField(): Select
    {
        return Select::make('owner_type')
            ->label('Owner Type')
            ->options([Employee::class => 'Employee'])
            ->reactive()
            ->required(fn (Get $get) => ! $get('is_global'))
            ->visible(fn (Get $get) => ! $get('is_global'))
            ->live()
            ->afterStateUpdated(fn (Set $set) => $set('owner_id', null))
            ->helperText('Select the type of owner for this account');
    }

    private static function ownerIdField(): Select
    {
        return Select::make('owner_id')
            ->label('Employee')
            ->options(fn (Get $get) => self::ownerOptions($get('owner_type')))
            ->searchable()
            ->required(fn (Get $get) => ! $get('is_global') && $get('owner_type'))
            ->visible(fn (Get $get) => ! $get('is_global') && $get('owner_type'))
            ->helperText('Choose the specific owner of this account');
    }

    private static function platformValidationRules(callable $get, ?Model $record): array
    {
        $ownerId = $get('owner_id');
        $ownerType = $get('owner_type');
        $isGlobal = $get('is_global');

        if ($isGlobal) {
            return [
                Rule::unique('social_media', 'platform')
                    ->whereNull('owner_id')
                    ->whereNull('owner_type')
                    ->ignore($record?->id ?? $record),
            ];
        }

        if (! $ownerId || ! $ownerType) {
            return [];
        }

        return [
            Rule::unique('social_media', 'platform')
                ->where('owner_id', $ownerId)
                ->where('owner_type', $ownerType)
                ->ignore($record?->id ?? $record),
        ];
    }

    private static function ownerOptions(?string $ownerType): array
    {
        return match ($ownerType) {
            Employee::class => Employee::with('user')->get()->pluck('user.name', 'id')->toArray(),
            default => [],
        };
    }
}
