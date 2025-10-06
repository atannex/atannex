<?php

declare(strict_types=1);

namespace App\Filament\Resources\Abouts\Schemas;

use App\Enums\Flag;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

/**
 * Form schema for the About resource.
 *
 * Defines the structure and validation rules for managing
 * organization information, company history, contact details,
 * media assets, and statistics.
 */
class AboutForm
{
    /**
     * Configure the About form schema.
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->components([
                self::getMainContentGroup(),
                self::getSidebarGroup(),
            ]);
    }

    /**
     * Get the main content group (left column).
     */
    private static function getMainContentGroup(): Group
    {
        return Group::make()
            ->columnSpan(['lg' => 8])
            ->schema([
                self::getBasicInformationSection(),
                self::getCompanyHistorySection(),
            ]);
    }

    /**
     * Get the sidebar group (right column).
     */
    private static function getSidebarGroup(): Group
    {
        return Group::make()
            ->columnSpan(['lg' => 4])
            ->schema([
                self::getStatusSection(),
                self::getContactSection(),
                self::getCtaSection(),
                self::getMediaAssetsSection(),
                self::getKeyFeaturesSection(),
                self::getStatisticsSection(),
            ]);
    }

    /**
     * Basic information section.
     */
    private static function getBasicInformationSection(): Section
    {
        return Section::make('Basic Information')
            ->description('Primary details about your organization')
            ->icon('heroicon-o-information-circle')
            ->collapsible()
            ->collapsed()
            ->schema([
                Grid::make(2)->schema([
                    TextInput::make('title')
                        ->label('Title')
                        ->placeholder('Enter main title')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('subtitle')
                        ->label('Subtitle')
                        ->placeholder('Enter subtitle (optional)')
                        ->maxLength(255),
                ]),

                RichEditor::make('description')
                    ->label('Description')
                    ->placeholder('Provide a detailed description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    /**
     * Company history timeline section.
     */
    private static function getCompanyHistorySection(): Section
    {
        return Section::make('Company History')
            ->description('Timeline of important milestones and events')
            ->icon('heroicon-o-clock')
            ->collapsible()
            ->schema([
                Repeater::make('story')
                    ->label('Timeline Events')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('year')
                                ->label('Year')
                                ->numeric()
                                ->required()
                                ->minValue(1800)
                                ->maxValue(2100)
                                ->placeholder('2024'),

                            TextInput::make('title')
                                ->label('Event Title')
                                ->placeholder('Company milestone')
                                ->required()
                                ->maxLength(255),
                        ]),

                        RichEditor::make('description')
                            ->label('Description')
                            ->placeholder('Describe this milestone')
                            ->maxLength(1000)
                            ->columnSpanFull(),

                        self::createImageUpload('image', 'abouts/story')
                            ->label('Event Image')
                            ->columnSpanFull(),
                    ])
                    ->addActionLabel('Add Timeline Event')
                    ->reorderable()
                    ->collapsed()
                    ->itemLabel(
                        fn(array $state): ?string => ($state['year'] ?? '') . ' - ' . ($state['title'] ?? 'New Event')
                    )
                    ->orderColumn('year'),
            ]);
    }

    /**
     * Publication status section.
     */
    private static function getStatusSection(): Section
    {
        return Section::make('Status & Map')
            ->description('Manage publication status & map')
            ->icon('heroicon-o-flag')
            ->collapsed()
            ->collapsible()
            ->schema([
                Select::make('flag')
                    ->label('Publication Status')
                    ->options(Flag::asSelectArray())
                    ->preload()
                    ->searchable()
                    ->default(Flag::PENDING)
                    ->required()
                    ->native(false),
                TextInput::make('map')
                    ->label('Map')
                    ->placeholder('https://www.google.com/maps'),
            ]);
    }

    /**
     * Contact information section.
     */
    private static function getContactSection(): Section
    {
        return Section::make('Contact')
            ->description('Manage your contact information')
            ->icon('heroicon-o-phone')
            ->collapsible()
            ->collapsed()
            ->schema([
                Repeater::make('info')
                    ->label('Contact Entries')
                    ->collapsible()
                    ->collapsed()
                    ->addActionLabel('Add New Contact')
                    ->itemLabel(
                        fn(array $state): ?string =>
                        $state['title'] ?? 'New Contact'
                    )
                    ->reorderable()
                    ->schema([
                        self::createImageUpload('icon', 'icons')
                            ->label('Contact Icon'),

                        TextInput::make('title')
                            ->label('Contact Title')
                            ->placeholder('e.g. Phone, Email, Location')
                            ->required()
                            ->maxLength(255),

                        Repeater::make('details')
                            ->label('Contact Details')
                            ->collapsible()
                            ->collapsed()
                            ->itemLabel(
                                fn(array $state): ?string => ($state['type'] ?? 'New') . ': ' . ($state['value'] ?? 'Detail')
                            )
                            ->schema([
                                Select::make('type')
                                    ->label('Type')
                                    ->searchable()
                                    ->preload()
                                    ->options([
                                        'phone' => 'Phone',
                                        'email' => 'Email',
                                        'location' => 'Location',
                                        'fax' => 'Fax',
                                    ])
                                    ->placeholder('Select contact type')
                                    ->required(),

                                TextInput::make('value')
                                    ->label('Detail')
                                    ->placeholder('e.g. +1 234 567 890, hello@email.com')
                                    ->maxLength(255)
                                    ->required(),
                            ]),
                    ]),
            ]);
    }

    /**
     * Call-to-action section.
     */
    private static function getCtaSection(): Section
    {
        return Section::make('CTA Section')
            ->description('Manage the Call-To-Action displayed on the About page')
            ->icon('heroicon-o-rocket-launch')
            ->collapsible()
            ->collapsed()
            ->schema([
                self::createImageUpload('cta.bg_image', 'cta')
                    ->label('Background Image'),

                TextInput::make('cta.title')
                    ->label('Title')
                    ->placeholder('Enter CTA title')
                    ->required()
                    ->maxLength(255),

                TextInput::make('cta.subtitle')
                    ->label('Subtitle')
                    ->placeholder('Enter CTA subtitle (optional)')
                    ->maxLength(255),
            ]);
    }

    /**
     * Media assets section (images and videos).
     */
    private static function getMediaAssetsSection(): Section
    {
        return Section::make('Media Assets')
            ->description('Upload images and add video content')
            ->icon('heroicon-o-photo')
            ->collapsible()
            ->collapsed()
            ->schema([
                Repeater::make('image')
                    ->label('Image Gallery')
                    ->schema([
                        self::createImageUpload('path', 'abouts/images')
                            ->label('Image'),
                    ])
                    ->addActionLabel('Add Image')
                    ->reorderable()
                    ->collapsed()
                    ->itemLabel(
                        fn(array $state): ?string =>
                        'Image ' . ($state['path'] ? '(Uploaded)' : '(New)')
                    )
                    ->grid(1),

                TextInput::make('video_url')
                    ->label('Video URL')
                    ->placeholder('https://www.youtube.com/watch?v=...')
                    ->url()
                    ->suffixIcon('heroicon-o-video-camera')
                    ->helperText('Paste a YouTube, Vimeo, or direct video URL'),
            ]);
    }

    /**
     * Key features section.
     */
    private static function getKeyFeaturesSection(): Section
    {
        return Section::make('Key Features')
            ->description('Highlight important features or benefits')
            ->icon('heroicon-o-star')
            ->collapsible()
            ->collapsed()
            ->schema([
                Repeater::make('features')
                    ->label('Features List')
                    ->schema([
                        TextInput::make('text')
                            ->label('Feature')
                            ->placeholder('Enter feature description')
                            ->required()
                            ->maxLength(500),
                    ])
                    ->addActionLabel('Add Feature')
                    ->reorderable()
                    ->collapsed()
                    ->itemLabel(
                        fn(array $state): ?string =>
                        $state['text'] ?? 'New Feature'
                    )
                    ->grid(1),
            ]);
    }

    /**
     * Statistics and counters section.
     */
    private static function getStatisticsSection(): Section
    {
        return Section::make('Statistics & Counters')
            ->description('Display impressive numbers and achievements')
            ->icon('heroicon-o-chart-bar')
            ->collapsible()
            ->collapsed()
            ->schema([
                Repeater::make('counters')
                    ->label('Counter Items')
                    ->schema([
                        TextInput::make('label')
                            ->label('Label')
                            ->placeholder('Years of Experience')
                            ->required()
                            ->maxLength(255),

                        Select::make('type')
                            ->label('Counter Type')
                            ->options([
                                'posts' => 'Number of Posts',
                                'users' => 'Number of Users',
                                'employees' => 'Number of Employees',
                                'manual' => 'Manual Number',
                                'years_experience' => 'Years of Experience (Auto)',
                                'awards' => 'Awards Count (Auto)',
                                'writers' => 'Writers Count (Auto)',
                                'translators' => 'Translators Count (Auto)',
                                'comments' => 'Number of Comments',
                            ])
                            ->default('manual')
                            ->required()
                            ->live()
                            ->native(false),

                        TextInput::make('number')
                            ->label('Manual Number')
                            ->numeric()
                            ->placeholder('100')
                            ->helperText('Enter the counter value')
                            ->visible(fn(Get $get): bool => $get('type') === 'manual'),

                        TextInput::make('base_year')
                            ->label('Base Year')
                            ->numeric()
                            ->placeholder('2010')
                            ->minValue(1900)
                            ->maxValue(2100)
                            ->helperText('Year when experience started')
                            ->visible(fn(Get $get): bool => $get('type') === 'years_experience'),
                    ])
                    ->addActionLabel('Add Counter')
                    ->reorderable()
                    ->collapsed()
                    ->itemLabel(
                        fn(array $state): ?string =>
                        $state['label'] ?? 'New Counter'
                    ),
            ]);
    }

    /**
     * Create a standardized image upload field.
     */
    private static function createImageUpload(string $name, string $directory): FileUpload
    {
        return FileUpload::make($name)
            ->disk('public')
            ->image()
            ->imageEditor()
            ->imageEditorAspectRatios([
                '16:9',
                '4:3',
                '1:1',
            ])
            ->maxSize(5120)
            ->directory($directory)
            ->required();
    }
}
