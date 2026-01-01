<?php

namespace App\Filament\Resources\Posts\Schemas;

use Illuminate\Support\Str;
use Filament\Schemas\Schema;
use App\Models\Regions\Category;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Components\DateTimePicker;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Post Content')
                    ->description('Create compelling content that engages your audience')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        TextInput::make('title')
                            ->label('Title')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Enter a captivating headline...')
                            ->columnSpanFull()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, $state, $set) {
                                if ($operation === 'create') {
                                    $set('slug', Str::slug($state));
                                }
                            })
                            ->helperText('Keep it concise and impactful (recommended: 50-60 characters)'),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('slug')
                                    ->label('URL Slug')
                                    ->disabled()
                                    ->dehydrated()
                                    ->placeholder('auto-generated-from-title')
                                    ->prefixIcon('heroicon-o-link')
                                    ->helperText('Automatically generated, SEO-friendly URL'),

                                TextInput::make('slug_path')
                                    ->label('Full Path')
                                    ->disabled()
                                    ->dehydrated()
                                    ->placeholder('/category/auto-generated-from-title')
                                    ->prefixIcon('heroicon-o-globe-alt')
                                    ->helperText('Complete URL path including category'),
                            ]),

                        Textarea::make('description')
                            ->label('Meta Description')
                            ->placeholder('Write a compelling summary that appears in search results and social media...')
                            ->rows(3)
                            ->maxLength(500)
                            ->columnSpanFull()
                            ->helperText('Optimal length: 150-160 characters for SEO. Current: 0 characters')
                            ->reactive()
                            ->afterStateUpdated(function ($state, $component) {
                                $length = strlen($state ?? '');
                                $component->helperText("Optimal length: 150-160 characters for SEO. Current: {$length} characters");
                            }),
                    ])
                    ->columnSpanFull()
                    ->collapsible()
                    ->persistCollapsed(),

                Grid::make(['default' => 1, 'lg' => 3])
                    ->schema([
                        Group::make()
                            ->schema([

                                Section::make('Organization & Assignment')
                                    ->description('Categorize and manage ownership')
                                    ->icon('heroicon-o-folder-open')
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
                                                Section::make('Create New Category')
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
                                                fn($action) => $action
                                                    ->modalHeading('Create Category')
                                                    ->modalDescription('Add a new category or subcategory to organize your content.')
                                                    ->modalSubmitActionLabel('Create Category')
                                                    ->modalWidth('lg')
                                            )
                                            ->helperText('Choose existing or create new. Parent → Child hierarchy supported.')
                                            ->native(false)
                                            ->prefixIcon('heroicon-o-folder'),

                                        Select::make('author_id')
                                            ->relationship('author.user', 'name')
                                            ->label('Author')
                                            ->required()
                                            ->searchable()
                                            ->preload()
                                            ->default(fn() => Auth::id())
                                            ->prefixIcon('heroicon-o-user-circle')
                                            ->helperText('Content creator assigned to this post'),
                                    ])
                                    ->compact()
                                    ->collapsible()
                                    ->persistCollapsed(),

                                Section::make('Publishing Schedule')
                                    ->description('Control publication timing and tracking')
                                    ->icon('heroicon-o-calendar')
                                    ->schema([
                                        DateTimePicker::make('published_at')
                                            ->label('Publish Date & Time')
                                            ->placeholder('Select publication date and time')
                                            ->helperText('Leave empty to publish immediately upon save')
                                            ->native(false)
                                            ->displayFormat('M d, Y - H:i')
                                            ->seconds(false)
                                            ->prefixIcon('heroicon-o-clock'),

                                        TextEntry::make('updated_tracking')
                                            ->label('Update Tracking')
                                            ->state(function ($record) {
                                                if (!$record) {
                                                    return 'Not yet created';
                                                }

                                                $updatedBy = $record->updatedBy->user->name ?? 'System';
                                                $updatedAt = $record->updated_at?->format('M d, Y - H:i') ?? 'N/A';

                                                return "Last modified by {$updatedBy} on {$updatedAt}";
                                            })
                                            ->helperText('Automatic tracking for audit and compliance'),

                                        TextInput::make('updated_by')
                                            ->label('Updated By (ID)')
                                            ->disabled()
                                            ->dehydrated(false)
                                            ->visible(fn($record) => $record !== null),
                                    ])
                                    ->compact()
                                    ->collapsible()
                                    ->persistCollapsed(),
                            ])
                            ->columnSpan(['default' => 1, 'lg' => 2]),

                        Group::make()
                            ->schema([

                                Section::make('Featured Media')
                                    ->description('Upload high-quality imagery')
                                    ->icon('heroicon-o-photo')
                                    ->schema([
                                        FileUpload::make('image')
                                            ->label('Featured Image')
                                            ->disk('public')
                                            ->visibility('public')
                                            ->directory(fn($record) => $record?->dir() ?? 'posts')
                                            ->image()
                                            ->imageEditor()
                                            ->imageEditorAspectRatios([
                                                '16:9' => '16:9 (Widescreen - Recommended)',
                                                '4:3'  => '4:3 (Standard)',
                                                '1:1'  => '1:1 (Square)',
                                                '21:9' => '21:9 (Ultrawide)',
                                            ])
                                            ->maxSize(5120)
                                            ->acceptedFileTypes([
                                                'image/jpeg',
                                                'image/png',
                                                'image/webp',
                                                'image/gif',
                                                'image/jpg',
                                                'image/svg+xml',
                                                'image/heic',
                                                'image/heif',
                                            ])
                                            ->helperText('Recommended: 1920×1080px (16:9) | Max size: 5MB | Formats: JPG, PNG, WebP, GIF')
                                            ->imagePreviewHeight('280')
                                            ->loadingIndicatorPosition('center')
                                            ->panelAspectRatio('16:9')
                                            ->panelLayout('integrated')
                                            ->removeUploadedFileButtonPosition('top-right')
                                            ->uploadProgressIndicatorPosition('center')
                                            ->columnSpanFull(),
                                    ])
                                    ->compact()
                                    ->collapsible()
                                    ->persistCollapsed(),

                                Section::make("Editor's Pick")
                                    ->description('Curate premium content for homepage spotlight')
                                    ->icon('heroicon-o-star')
                                    ->schema([
                                        Toggle::make('is_editor_pick')
                                            ->label("Feature as Editor's Pick")
                                            ->inline(false)
                                            ->helperText('Showcase this post in premium editorial sections')
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, $set) {
                                                if ($state) {
                                                    $set('editor_pick_at', now());
                                                    $set('editor_pick_expires', now()->addDays(7));
                                                } else {
                                                    $set('editor_pick_at', null);
                                                    $set('editor_pick_expires', null);
                                                }
                                            }),

                                        Grid::make(2)
                                            ->schema([
                                                DateTimePicker::make('editor_pick_at')
                                                    ->label('Featured Since')
                                                    ->native(false)
                                                    ->displayFormat('M d, Y - H:i')
                                                    ->seconds(false)
                                                    ->visible(fn($get) => $get('is_editor_pick'))
                                                    ->helperText('When this was selected'),

                                                DateTimePicker::make('editor_pick_expires')
                                                    ->label('Feature Until')
                                                    ->native(false)
                                                    ->displayFormat('M d, Y - H:i')
                                                    ->seconds(false)
                                                    ->visible(fn($get) => $get('is_editor_pick'))
                                                    ->helperText('Auto-remove after this date')
                                                    ->minDate(fn($get) => $get('editor_pick_at')),
                                            ])
                                            ->visible(fn($get) => $get('is_editor_pick')),
                                    ])
                                    ->compact()
                                    ->collapsed()
                                    ->persistCollapsed(),

                                Section::make('Breaking News')
                                    ->description('Highlight urgent, time-sensitive content')
                                    ->icon('heroicon-o-bolt')
                                    ->schema([
                                        Toggle::make('is_breaking')
                                            ->label('Mark as Breaking News')
                                            ->inline(false)
                                            ->helperText('Display with breaking news badge and priority placement')
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, $set) {
                                                if ($state) {
                                                    $set('breaking_at', now());
                                                    $set('breaking_expires', now()->addHours(24));
                                                } else {
                                                    $set('breaking_at', null);
                                                    $set('breaking_expires', null);
                                                }
                                            }),

                                        Grid::make(2)
                                            ->schema([
                                                DateTimePicker::make('breaking_at')
                                                    ->label('Breaking Since')
                                                    ->native(false)
                                                    ->displayFormat('M d, Y - H:i')
                                                    ->seconds(false)
                                                    ->visible(fn($get) => $get('is_breaking'))
                                                    ->helperText('When this became breaking news'),

                                                DateTimePicker::make('breaking_expires')
                                                    ->label('Expires At')
                                                    ->native(false)
                                                    ->displayFormat('M d, Y - H:i')
                                                    ->seconds(false)
                                                    ->visible(fn($get) => $get('is_breaking'))
                                                    ->helperText('Auto-remove breaking status after this time')
                                                    ->minDate(fn($get) => $get('breaking_at')),
                                            ])
                                            ->visible(fn($get) => $get('is_breaking')),
                                    ])
                                    ->compact()
                                    ->collapsed()
                                    ->persistCollapsed(),
                            ])
                            ->columnSpan(['default' => 1, 'lg' => 1]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
