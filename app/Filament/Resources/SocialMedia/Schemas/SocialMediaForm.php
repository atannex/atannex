<?php

namespace App\Filament\Resources\SocialMedia\Schemas;

use App\Enums\Flag;
use App\Enums\Icons;
use App\Filament\Traits\HasEnumColumnAndField;
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
    use HasEnumColumnAndField;

    /**
     * Configure the social media form schema.
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::createMainContentGroup(),
                self::createOwnershipGroup(),
            ])
            ->columns(3);
    }

    /**
     * Create the main content group with basic information and platform settings.
     */
    private static function createMainContentGroup(): Group
    {
        return Group::make([
            self::createBasicInformationSection(),
            self::createPlatformSettingsSection(),
        ])->columnSpan(2);
    }

    /**
     * Create the basic information section.
     */
    private static function createBasicInformationSection(): Section
    {
        return Section::make('Basic Information')
            ->description('Configure your social media account details')
            ->icon('heroicon-o-identification')
            ->schema([
                Grid::make(2)->schema([
                    self::createAccountLabelField(),
                    self::createUrlField(),
                ]),
            ]);
    }

    /**
     * Create the platform settings section.
     */
    private static function createPlatformSettingsSection(): Section
    {
        return Section::make('Platform & Settings')
            ->description('Select platform and configure account settings')
            ->icon('heroicon-o-cog-6-tooth')
            ->schema([
                Grid::make(3)->schema([
                    self::createPlatformField(),
                    self::createStatusFlagField(),
                    self::createOrderField(),
                ]),
            ]);
    }

    /**
     * Create the ownership group.
     */
    private static function createOwnershipGroup(): Group
    {
        return Group::make([
            self::createOwnershipSection(),
        ])->columnSpan(1);
    }

    /**
     * Create the ownership section.
     */
    private static function createOwnershipSection(): Section
    {
        return Section::make('Account Ownership')
            ->description('Define who owns and manages this account')
            ->icon('heroicon-o-user-group')
            ->schema([
                Grid::make(1)->schema([
                    self::createGlobalToggleField(),
                    self::createOwnerTypeField(),
                    self::createOwnerIdField(),
                ]),
            ]);
    }

    /**
     * Create the account label field.
     */
    private static function createAccountLabelField(): TextInput
    {
        return TextInput::make('label')
            ->label('Account Label')
            ->placeholder('e.g., Main Facebook Page')
            ->helperText('A descriptive name for this social media account')
            ->required()
            ->maxLength(255);
    }

    /**
     * Create the URL field.
     */
    private static function createUrlField(): TextInput
    {
        return TextInput::make('url')
            ->label('Social Media URL')
            ->placeholder('https://facebook.com/your-page')
            ->helperText('The complete URL to your social media profile')
            ->url()
            ->required()
            ->maxLength(255); // Updated to match migration constraint
    }

    /**
     * Create the platform field with unique validation.
     */
    private static function createPlatformField(): Component
    {
        return self::makeEnumField('platform', Icons::class, default: Icons::FACEBOOK)
            ->label('Social Platform')
            ->helperText('Choose the social media platform')
            ->reactive()
            ->rules([
                function (callable $get, ?Model $record): array {
                    return self::getPlatformValidationRules($get, $record);
                },
            ])
            ->validationMessages([
                'unique' => 'This platform is already registered for the selected owner/type combination.',
            ]);
    }

    /**
     * Create the status flag field.
     */
    private static function createStatusFlagField(): Component
    {
        return self::makeEnumField('flag', Flag::class, default: Flag::PENDING)
            ->label('Status Flag')
            ->helperText('Current status of this account');
    }

    /**
     * Create the order field.
     */
    private static function createOrderField(): TextInput
    {
        return TextInput::make('order')
            ->label('Display Order')
            ->helperText('Order for displaying this account')
            ->required()
            ->numeric()
            ->default(0)
            ->minValue(0);
    }

    /**
     * Create the global toggle field.
     */
    private static function createGlobalToggleField(): Toggle
    {
        return Toggle::make('is_global')
            ->label('Global Icon')
            ->live()
            ->afterStateUpdated(function (Set $set, $state) {
                if ($state) {
                    // When global is enabled, clear owner fields
                    $set('owner_id', null);
                    $set('owner_type', null);
                }
            })
            ->default(false);
    }

    /**
     * Create the owner type field.
     */
    private static function createOwnerTypeField(): Select
    {
        return Select::make('owner_type')
            ->label('Owner Type')
            ->options([Employee::class => 'Employee'])
            ->reactive()
            ->required(fn(Get $get) => $get('is_global') === false)
            ->visible(fn(Get $get) => $get('is_global') === false)
            ->live()
            ->afterStateUpdated(function (Set $set, $state) {
                // Clear owner_id when owner_type changes
                $set('owner_id', null);
            })
            ->helperText('Select the type of owner for this account');
    }

    /**
     * Create the owner ID field.
     */
    private static function createOwnerIdField(): Select
    {
        return Select::make('owner_id')
            ->label('Employee')
            ->options(fn(Get $get) => self::getOwnerOptions($get('owner_type')))
            ->searchable()
            ->required(fn(Get $get) => !$get('is_global') && $get('owner_type'))
            ->visible(fn(Get $get) => !$get('is_global') && $get('owner_type'))
            ->helperText('Choose the specific owner of this account');
    }

    /**
     * Get platform validation rules based on the unique constraint.
     * Handles the composite unique constraint: ['owner_id', 'owner_type', 'platform']
     */
    private static function getPlatformValidationRules(callable $get, ?Model $record): array
    {
        $ownerId = $get('owner_id');
        $ownerType = $get('owner_type');
        $isGlobal = $get('is_global');

        // For global accounts, validate uniqueness where owner_id and owner_type are both null
        if ($isGlobal) {
            return [
                Rule::unique('social_media', 'platform')
                    ->whereNull('owner_id')
                    ->whereNull('owner_type')
                    ->ignore($record?->id ?? $record)
            ];
        }

        // For non-global accounts, we need both owner_id and owner_type to validate
        // the composite unique constraint
        if (!$ownerId || !$ownerType) {
            return []; // Skip validation until both fields are filled
        }

        return [
            Rule::unique('social_media', 'platform')
                ->where('owner_id', $ownerId)
                ->where('owner_type', $ownerType)
                ->ignore($record?->id ?? $record)
        ];
    }

    /**
     * Get owner options based on the owner type.
     */
    private static function getOwnerOptions(?string $ownerType): array
    {
        if (!$ownerType) {
            return [];
        }

        return match ($ownerType) {
            Employee::class => Employee::with('user')
                ->get()
                ->pluck('user.name', 'id')
                ->toArray(),
            default => [],
        };
    }
}
