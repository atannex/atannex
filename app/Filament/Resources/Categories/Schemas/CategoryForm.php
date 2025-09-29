<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Enums\Flag;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    /**
     * Configure the category form schema with professional features and improved UX.
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Category Details')
                    ->tabs([
                        Tab::make('Basic Information')
                            ->icon('heroicon-m-information-circle')
                            ->schema([
                                Section::make('Category Details')
                                    ->description('Basic information about the category')
                                    ->icon('heroicon-m-tag')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                TextInput::make('name')
                                                    ->label('Category Name')
                                                    ->required()
                                                    ->maxLength(255)
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(function (string $operation, $state, $set) {
                                                        if ($operation !== 'create') {
                                                            return;
                                                        }

                                                        $set('slug', Str::slug($state));
                                                    })
                                                    ->helperText('Enter a descriptive name for the category')
                                                    ->placeholder('e.g., Electronics, Clothing, Books'),

                                                TextInput::make('slug')
                                                    ->label('URL Slug')
                                                    ->disabled()
                                                    ->dehydrated()
                                                    ->maxLength(255)
                                                    ->unique(ignoreRecord: true)
                                                    ->helperText('Auto-generated from the category name')
                                                    ->prefixIcon('heroicon-m-link'),
                                            ]),

                                        Textarea::make('description')
                                            ->label('Description')
                                            ->placeholder('Provide a detailed description of this category...')
                                            ->helperText('This description may be used for SEO and category listings')
                                            ->rows(4)
                                            ->columnSpanFull(),
                                    ]),

                                Section::make('Category Hierarchy')
                                    ->description('Set the parent category and organization')
                                    ->icon('heroicon-m-squares-plus')
                                    ->schema([
                                        Select::make('parent_id')
                                            ->label('Parent Category')
                                            ->relationship('parent', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->nullable()
                                            ->placeholder('Select a parent category (optional)')
                                            ->helperText('Leave empty to create a root category')
                                            ->createOptionForm([
                                                TextInput::make('name')
                                                    ->required()
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(fn (string $operation, $state, $set) =>
                                                        $operation === 'create' ? $set('slug', Str::slug($state)) : null
                                                    ),
                                                TextInput::make('slug')
                                                    ->required()
                                                    ->unique(),
                                            ])
                                            ->createOptionAction(function ($action) {
                                                return $action
                                                    ->modalHeading('Create New Parent Category')
                                                    ->modalSubmitActionLabel('Create Category');
                                            }),
                                    ]),
                            ]),

                        Tab::make('Media & Status')
                            ->icon('heroicon-m-photo')
                            ->schema([
                                Section::make('Category Image')
                                    ->description('Upload an image to represent this category')
                                    ->icon('heroicon-m-camera')
                                    ->schema([
                                        FileUpload::make('image')
                                            ->label('Category Image')
                                            ->image()
                                            ->imageEditor()
                                            ->imageEditorAspectRatios([
                                                '16:9',
                                                '4:3',
                                                '1:1',
                                            ])
                                            ->maxSize(2048)
                                            ->acceptedFileTypes(['image/png', 'image/jpg', 'image/jpeg', 'image/webp'])
                                            ->helperText('Recommended size: 800x600px. Max file size: 2MB')
                                            ->imagePreviewHeight('250')
                                            ->loadingIndicatorPosition('left')
                                            ->panelAspectRatio('2:1')
                                            ->panelLayout('integrated')
                                            ->removeUploadedFileButtonPosition('right')
                                            ->uploadButtonPosition('left')
                                            ->uploadProgressIndicatorPosition('left'),
                                    ]),

                                Section::make('Status & Publishing')
                                    ->description('Control category visibility and status')
                                    ->icon('heroicon-m-eye')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Select::make('flag')
                                                    ->label('Status Flag')
                                                    ->required()
                                                    ->default('pending')
                                                    ->options(Flag::labels())
                                                    ->searchable()
                                                    ->helperText('Select the current status of this category'),

                                                DateTimePicker::make('published_at')
                                                    ->label('Publication Date')
                                                    ->placeholder('Select publication date')
                                                    ->helperText('Leave empty to keep as draft')
                                                    ->displayFormat('M j, Y g:i A')
                                                    ->native(false)
                                                    ->suffixIcon('heroicon-m-calendar-days')
                                                    ->closeOnDateSelection()
                                                    ->default(now()),
                                            ])
                                    ]),
                            ]),

                        Tab::make('SEO & Meta')
                            ->icon('heroicon-m-magnifying-glass')
                            ->schema([
                                Section::make('Search Engine Optimization')
                                    ->description('Improve search engine visibility')
                                    ->icon('heroicon-m-globe-alt')
                                    ->schema([
                                        TextInput::make('meta_title')
                                            ->label('Meta Title')
                                            ->maxLength(60)
                                            ->helperText('Recommended: 50-60 characters')
                                            ->placeholder('SEO-friendly title for search engines')
                                            ->live()
                                            ->afterStateUpdated(function ($state, $set, $get) {
                                                if (!$get('meta_title') && $get('name')) {
                                                    $set('meta_title', $get('name'));
                                                }
                                            }),

                                        Textarea::make('meta_description')
                                            ->label('Meta Description')
                                            ->maxLength(160)
                                            ->helperText('Recommended: 150-160 characters')
                                            ->placeholder('Brief description for search engine results')
                                            ->rows(3),

                                        TextInput::make('meta_keywords')
                                            ->label('Meta Keywords')
                                            ->helperText('Comma-separated keywords (optional)')
                                            ->placeholder('electronics, gadgets, technology'),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull()
                    ->persistTabInQueryString(),
            ]);
    }
}
