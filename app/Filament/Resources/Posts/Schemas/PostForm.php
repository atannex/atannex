<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Enums\Flag;
use App\Models\Regions\Category;
use App\Models\Regions\Region;
use App\Models\Tags\Tag;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->schema([
                        Section::make('Post Content')
                            ->description('Create and edit your post content')
                            ->icon('heroicon-o-pencil-square')
                            ->schema([
                                Grid::make(12)
                                    ->schema([
                                        TextInput::make('title')
                                            ->label('Title')
                                            ->required()
                                            ->maxLength(255)
                                            ->placeholder('Enter your post title...')
                                            ->columnSpan(['default' => 12, 'md' => 8, 'lg' => 9])
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (string $operation, $state, $set) {
                                                if ($operation === 'create') {
                                                    $set('slug', Str::slug($state));
                                                }
                                            }),

                                        Select::make('flag')
                                            ->label('Status Flag')
                                            ->helperText('Current status of this account')
                                            ->options(Flag::asSelectArray())
                                            ->searchable()
                                            ->required()
                                            ->preload()
                                            ->columnSpan(['default' => 12, 'md' => 4, 'lg' => 3])
                                            ->default(Flag::PENDING),
                                    ]),
                                Grid::make(12)
                                    ->schema([
                                        TextInput::make('slug')
                                            ->label('URL Slug')
                                            ->disabled()
                                            ->dehydrated()
                                            ->placeholder('Auto-generated from title')
                                            ->helperText('This URL-friendly version is automatically created from your title')
                                            ->columnSpan(['default' => 12, 'md' => 6, 'lg' => 6])
                                            ->prefixIcon('heroicon-o-link'),
                                        TextInput::make('slug_path')
                                            ->label('Post Slug')
                                            ->disabled()
                                            ->dehydrated()
                                            ->placeholder('Auto-generated from title')
                                            ->helperText('This is the Post Slug Path')
                                            ->prefixIcon('heroicon-o-link')
                                            ->columnSpan(['default' => 12, 'md' => 6, 'lg' => 6]),

                                    ]),

                                Textarea::make('description')
                                    ->label('Description')
                                    ->placeholder('Write a brief description of your post for SEO and social media...')
                                    ->rows(4)
                                    ->maxLength(500)
                                    ->helperText('Recommended: 150-160 characters for optimal SEO')
                                    ->columnSpanFull(),
                            ])
                            ->collapsible()
                            ->persistCollapsed(),
                    ])
                    ->columnSpanFull(),

                Grid::make(2)
                    ->schema([
                        Group::make()
                            ->schema([
                                Section::make('Organization')
                                    ->description('Categorize and assign your post')
                                    ->icon('heroicon-o-squares-2x2')
                                    ->schema([
                                        Grid::make(1)
                                            ->schema([
                                                Select::make('category_id')
                                                    ->label('Category')
                                                    ->required()
                                                    ->searchable()
                                                    ->preload()
                                                    ->options(function () {
                                                        return Category::with('parent')
                                                            ->get()
                                                            ->mapWithKeys(function ($category) {
                                                                $label = $category->parent
                                                                    ? sprintf('%s → %s', $category->parent->name, $category->name)
                                                                    : $category->name;

                                                                return [$category->id => $label];
                                                            })
                                                            ->toArray();
                                                    })
                                                    ->createOptionForm([
                                                        Section::make('Create Category')
                                                            ->schema([
                                                                TextInput::make('name')
                                                                    ->label('Category Name')
                                                                    ->required()
                                                                    ->maxLength(255)
                                                                    ->placeholder('Enter category name'),

                                                                Select::make('parent_id')
                                                                    ->label('Parent Category (optional)')
                                                                    ->options(
                                                                        Category::pluck('name', 'id')->toArray()
                                                                    )
                                                                    ->searchable()
                                                                    ->preload()
                                                                    ->placeholder('Select parent category'),
                                                            ]),
                                                    ])
                                                    ->createOptionModalHeading('Create New Category')
                                                    ->createOptionAction(
                                                        fn ($action) => $action
                                                            ->modalHeading('Create Category')
                                                            ->modalDescription('Add a new category or subcategory.')
                                                            ->modalSubmitActionLabel('Save Category')
                                                            ->modalWidth('lg')
                                                    )
                                                    ->helperText('Choose or create a category. Parent → Child structure is shown.')
                                                    ->native(false)
                                                    ->prefixIcon('heroicon-o-folder'),

                                                Select::make('author_id')
                                                    ->relationship('author.user', 'name')
                                                    ->searchable()
                                                    ->required()
                                                    ->preload(),
                                            ]),
                                    ])
                                    ->compact()
                                    ->collapsible()
                                    ->persistCollapsed(),

                                Section::make('Publishing Settings')
                                    ->description('Control when your post goes live')
                                    ->icon('heroicon-o-calendar-days')
                                    ->schema([
                                        Grid::make(1)
                                            ->schema([
                                                DateTimePicker::make('published_at')
                                                    ->label('Publish Date & Time')
                                                    ->placeholder('Select date and time')
                                                    ->helperText('Leave empty to publish immediately')
                                                    ->native(false)
                                                    ->displayFormat('M d, Y - H:i')
                                                    ->seconds(false),

                                                TextInput::make('updated_by')
                                                    ->label('Last Updated By')
                                                    ->disabled()
                                                    ->placeholder('User ID')
                                                    ->helperText('Tracking field for audit purposes'),

                                                Toggle::make('is_breaking')
                                                    ->label('Breaking News')
                                                    ->default(false)
                                                    ->reactive()
                                                    ->helperText('Enable this to mark the post as breaking news.'),

                                                DateTimePicker::make('breaking_until')
                                                    ->label('Breaking Until')
                                                    ->placeholder('Select date and time')
                                                    ->displayFormat('M d, Y - H:i')
                                                    ->native(false)
                                                    ->visible(fn ($get) => $get('is_breaking'))
                                                    ->required(fn ($get) => $get('is_breaking')),

                                            ])->columns(2),
                                    ])
                                    ->compact()
                                    ->collapsible()
                                    ->persistCollapsed(),
                            ])
                            ->columnSpan(1),
                        Group::make()
                            ->schema([

                                Section::make('Tags & Regions')
                                    ->icon('heroicon-o-tag')
                                    ->description('Add relevant tags and regions to optimize your post for search engines and improve discoverability.')
                                    ->schema([

                                        Select::make('regions')
                                            ->relationship('regions', 'name')
                                            ->multiple()
                                            ->preload()
                                            ->searchable()
                                            ->optionsLimit(50)
                                            ->helperText('Choose one or more regions to categorize your post effectively.')
                                            ->placeholder('Select regions')
                                            ->required()
                                            ->createOptionForm([
                                                TextInput::make('name')
                                                    ->required()
                                                    ->maxLength(255)
                                                    ->unique('regions', 'name'),
                                            ])
                                            ->createOptionUsing(function (array $data) {
                                                return Region::create($data)->id;
                                            })
                                            ->createOptionAction(fn ($action) => $action->label('Add New Region')),

                                        Select::make('tags')
                                            ->relationship('tags', 'name')
                                            ->multiple()
                                            ->preload()
                                            ->searchable()
                                            ->optionsLimit(50)
                                            ->helperText('Choose one or more tags to categorize your post effectively.')
                                            ->placeholder('Select tags')
                                            ->required()
                                            ->createOptionForm([
                                                TextInput::make('name')
                                                    ->required()
                                                    ->maxLength(255)
                                                    ->unique('tags', 'name'),
                                            ])
                                            ->createOptionUsing(function (array $data) {
                                                return Tag::create($data)->id;
                                            })
                                            ->createOptionAction(fn ($action) => $action->label('Add New Tag')),
                                    ])
                                    ->collapsible()
                                    ->compact(),
                                Section::make('Featured Media')
                                    ->description('Upload and manage your post images')
                                    ->icon('heroicon-o-photo')
                                    ->schema([
                                        FileUpload::make('image')
                                            ->label('Featured Image')
                                            ->disk('public')
                                            ->visibility('public')
                                            ->directory(fn ($record) => $record?->getImageDirectory())
                                            ->image()
                                            ->imageEditor()
                                            ->imageEditorAspectRatios([
                                                '16:9' => '16:9 (Recommended)',
                                                '4:3' => '4:3 (Standard)',
                                                '1:1' => '1:1 (Square)',
                                            ])
                                            ->maxSize(5120)
                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                            ->helperText('Recommended size: 1200x675px (16:9 ratio)')
                                            ->imagePreviewHeight('250')
                                            ->uploadingMessage('Uploading your image...')
                                            ->columnSpanFull(),
                                    ])
                                    ->compact()
                                    ->collapsible()
                                    ->persistCollapsed(),

                                Section::make('SEO & Metadata')
                                    ->description('Optimize your post for search engines')
                                    ->icon('heroicon-o-magnifying-glass')
                                    ->schema([
                                        Grid::make(1)
                                            ->schema([
                                                TextInput::make('meta_title')
                                                    ->label('Meta Title')
                                                    ->placeholder('Custom title for search results')
                                                    ->maxLength(60)
                                                    ->helperText('Leave empty to use post title'),

                                                Textarea::make('meta_description')
                                                    ->label('Meta Description')
                                                    ->placeholder('Custom description for search results')
                                                    ->rows(3)
                                                    ->maxLength(160)
                                                    ->helperText('Leave empty to use post description'),

                                                TextInput::make('canonical_url')
                                                    ->label('Canonical URL')
                                                    ->placeholder('https://example.com/original-post')
                                                    ->url()
                                                    ->helperText('Optional: Link to original source if reposting'),
                                            ]),
                                    ])
                                    ->compact()
                                    ->collapsible()
                                    ->collapsed(),
                            ])
                            ->columnSpan(1),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
