<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Enums\Flag;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->schema([
                        Section::make('Category Details')
                            ->description('Essential information about the category')
                            ->icon('heroicon-m-tag')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Category Name')
                                            ->maxLength(255)
                                            ->helperText('Enter a clear, descriptive name for the category')
                                            ->placeholder('e.g., Electronics, Home & Garden, Fashion')
                                            ->prefixIcon('heroicon-m-tag')
                                            ->autocomplete(false),

                                        TextInput::make('slug')
                                            ->label('URL Slug')
                                            ->disabled()
                                            ->maxLength(255)
                                            ->helperText('Auto-generated from category name (editable)')
                                            ->prefixIcon('heroicon-m-link')
                                            ->rules(['alpha_dash'])
                                            ->placeholder('auto-generated-slug'),
                                    ]),

                                Textarea::make('description')
                                    ->label('Category Description')
                                    ->placeholder('Write a comprehensive description of this category...')
                                    ->helperText('Used for SEO and category pages (recommended: 150-300 characters)')
                                    ->rows(4)
                                    ->maxLength(1000)
                                    ->columnSpanFull(),
                            ])
                            ->collapsible()
                            ->persistCollapsed(),

                        Section::make('Category Hierarchy & Organization')
                            ->description('Organize categories with parent-child relationships')
                            ->icon('heroicon-m-squares-plus')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        Select::make('parent_id')
                                            ->label('Parent Category')
                                            ->relationship('parent', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->nullable()
                                            ->placeholder('Select a parent category (optional)')
                                            ->helperText('Leave empty to create a root-level category')
                                            ->prefixIcon('heroicon-m-folder-open')
                                            ->native(false)
                                            ->createOptionForm([
                                                TextInput::make('name')
                                                    ->label('Category Name')
                                                    ->required()
                                                    ->maxLength(255)
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(function (string $operation, ?string $state, Set $set): void {
                                                        if ($operation === 'create' && filled($state)) {
                                                            $set('slug', Str::slug($state));
                                                        }
                                                    })
                                                    ->placeholder('Enter parent category name'),

                                                TextInput::make('slug')
                                                    ->label('URL Slug')
                                                    ->required()
                                                    ->unique()
                                                    ->maxLength(255)
                                                    ->placeholder('auto-generated-slug'),
                                            ])
                                            ->createOptionAction(
                                                fn($action) => $action
                                                    ->modalHeading('Create New Parent Category')
                                                    ->modalDescription('Add a new parent category for hierarchical organization')
                                                    ->modalSubmitActionLabel('Create Category')
                                                    ->modalWidth('lg')
                                            ),

                                        TextInput::make('order')
                                            ->label('Display Order')
                                            ->numeric()
                                            ->default(0)
                                            ->minValue(0)
                                            ->step(1)
                                            ->helperText('Lower numbers appear first (0 = highest priority)')
                                            ->placeholder('0')
                                            ->prefixIcon('heroicon-m-arrows-up-down'),
                                    ]),
                            ])
                            ->collapsible()
                            ->persistCollapsed(),

                        Section::make('Category Image')
                            ->description('Upload a visual representation for this category')
                            ->icon('heroicon-m-camera')
                            ->schema([
                                FileUpload::make('image')
                                    ->label('Category Image')
                                    ->disk('public')
                                    ->directory(fn($record) => $record?->dir() ?? 'category')
                                    ->visibility('public')
                                    ->image()
                                    ->imageEditor()
                                    ->imageEditorAspectRatios([
                                        '16:9' => '16:9 (Landscape)',
                                        '4:3'  => '4:3 (Standard)',
                                        '1:1'  => '1:1 (Square)',
                                        '9:16' => '9:16 (Portrait)',
                                    ])
                                    ->maxSize(5120)
                                    ->acceptedFileTypes([
                                        'image/png',
                                        'image/jpg',
                                        'image/jpeg',
                                        'image/webp',
                                        'image/svg+xml',
                                    ])
                                    ->helperText('Recommended: 1200×800px or larger. Max 5MB.')
                                    ->imagePreviewHeight('300')
                                    ->loadingIndicatorPosition('center')
                                    ->panelAspectRatio('16:9')
                                    ->panelLayout('integrated')
                                    ->removeUploadedFileButtonPosition('top-right')
                                    ->uploadButtonPosition('left')
                                    ->uploadProgressIndicatorPosition('center')
                                    ->columnSpanFull(),
                            ])
                            ->collapsible()
                            ->persistCollapsed(),
                    ])
                    ->columnSpan(['lg' => 2]),

                Group::make()
                    ->schema([
                        Section::make('Status & Publishing')
                            ->description('Control category visibility')
                            ->icon('heroicon-m-eye')
                            ->schema([
                                Select::make('flag')
                                    ->label('Status Flag')
                                    ->required()
                                    ->default('pending')
                                    ->options(Flag::labels())
                                    ->native(false)
                                    ->searchable()
                                    ->helperText('Current publication status')
                                    ->prefixIcon('heroicon-m-flag'),

                                DateTimePicker::make('published_at')
                                    ->label('Publication Date')
                                    ->placeholder('Select date and time')
                                    ->helperText('Schedule for future or leave empty for draft')
                                    ->displayFormat('M j, Y g:i A')
                                    ->native(false)
                                    ->suffixIcon('heroicon-m-calendar-days')
                                    ->closeOnDateSelection()
                                    ->seconds(false)
                                    ->default(now()),

                                Toggle::make('is_featured')
                                    ->label('Featured Category')
                                    ->helperText('Highlight on homepage')
                                    ->inline(false)
                                    ->default(false),
                            ])
                            ->collapsible()
                            ->persistCollapsed(),

                        Section::make('Social Media Preview')
                            ->description('Control social sharing appearance')
                            ->icon('heroicon-m-share')
                            ->schema([
                                TextInput::make('og_title')
                                    ->label('Social Title')
                                    ->maxLength(95)
                                    ->helperText('Title for social sharing')
                                    ->placeholder('Defaults to meta title')
                                    ->prefixIcon('heroicon-m-share'),

                                Textarea::make('og_description')
                                    ->label('Social Description')
                                    ->maxLength(200)
                                    ->helperText('Description for social sharing')
                                    ->placeholder('Defaults to meta description')
                                    ->rows(3),
                            ])
                            ->collapsible()
                            ->collapsed()
                            ->persistCollapsed(),

                        Section::make('Search Engine Optimization')
                            ->description('Optimize your category for search engines and social media')
                            ->icon('heroicon-m-magnifying-glass')
                            ->schema([
                                Grid::make(1)
                                    ->schema([
                                        TextInput::make('meta_title')
                                            ->label('Meta Title')
                                            ->maxLength(60)
                                            ->helperText('Recommended: 50-60 characters for optimal search results')
                                            ->placeholder('Enter SEO-optimized title (defaults to category name)')
                                            ->prefixIcon('heroicon-m-document-text')
                                            ->live(debounce: 500)
                                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                                if (empty($state) && $get('name')) {
                                                    $set('meta_title', $get('name'));
                                                }
                                            }),

                                        Textarea::make('meta_description')
                                            ->label('Meta Description')
                                            ->maxLength(160)
                                            ->helperText('Recommended: 150-160 characters for search snippets')
                                            ->placeholder('Write a compelling description for search results...')
                                            ->rows(3),

                                        TextInput::make('meta_keywords')
                                            ->label('Meta Keywords')
                                            ->helperText('Comma-separated keywords (e.g., electronics, gadgets, tech)')
                                            ->placeholder('keyword1, keyword2, keyword3')
                                            ->prefixIcon('heroicon-m-hashtag'),
                                    ]),
                            ])
                            ->collapsible()
                            ->collapsed()
                            ->persistCollapsed(),

                        Section::make('Timestamps')
                            ->description('Record creation and update history')
                            ->icon('heroicon-m-clock')
                            ->schema([
                                TextEntry::make('created_at')
                                    ->label('Created')
                                    ->state(fn($record) => $record?->created_at?->format('M j, Y g:i A') ?? 'Not yet created'),

                                TextEntry::make('updated_at')
                                    ->label('Last Updated')
                                    ->state(fn($record) => $record?->updated_at?->format('M j, Y g:i A') ?? 'Not yet updated'),
                            ])
                            ->collapsible()
                            ->collapsed()
                            ->persistCollapsed(),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }
}
