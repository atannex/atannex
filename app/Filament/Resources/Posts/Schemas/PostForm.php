<?php

namespace App\Filament\Resources\Posts\Schemas;

use Illuminate\Support\Str;
use Filament\Schemas\Schema;
use App\Models\Regions\Region;
use App\Models\Regions\Category;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Components\DateTimePicker;

class PostForm
{
    /**
     * Builds and returns a beautifully redesigned Filament form Schema for creating and editing posts.
     *
     * Features enhanced UX with visual indicators, character counters, smart defaults, decorative elements,
     * SEO optimization helpers, social media preview, and streamlined workflows.
     *
     * @param Schema $schema The base Schema instance to augment with post form components.
     * @return Schema The configured Schema containing the complete post form.
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('✨ Create Your Story')
                    ->description('Craft compelling content that captivates your audience')
                    ->icon('heroicon-o-sparkles')
                    ->iconColor('primary')
                    ->schema([
                        Grid::make(['default' => 1])
                            ->schema([
                                TextInput::make('title')
                                    ->label('Headline')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Write a headline that stops the scroll...')
                                    ->columnSpanFull()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (string $operation, $state, $set) {
                                        if ($operation === 'create') {
                                            $set('slug', Str::slug($state));
                                        }
                                    })
                                    ->helperText(
                                        fn($state) =>
                                        'Length: ' . strlen($state ?? '') . ' characters' .
                                            (strlen($state ?? '') > 0 && strlen($state ?? '') < 50
                                                ? ' ⚠️ Too short - aim for 50-60'
                                                : (strlen($state ?? '') > 70
                                                    ? ' ⚠️ Too long - keep under 70'
                                                    : ' ✓ Perfect length!'))
                                    )
                                    ->extraAttributes(['class' => 'text-lg font-semibold']),

                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('slug')
                                            ->label('🔗 URL Slug')
                                            ->disabled()
                                            ->dehydrated()
                                            ->placeholder('auto-generated-from-title')
                                            ->prefixIcon('heroicon-o-link'),

                                        TextInput::make('slug_path')
                                            ->label('🌐 Full Path Preview')
                                            ->disabled()
                                            ->dehydrated()
                                            ->placeholder('/category/auto-generated-from-title')
                                            ->prefixIcon('heroicon-o-globe-alt')
                                            ->extraAttributes(['class' => 'font-mono text-xs']),
                                    ]),

                                Textarea::make('description')
                                    ->label('Meta Description & Social Preview')
                                    ->placeholder('This appears in Google search results and when shared on social media. Make it compelling!')
                                    ->rows(4)
                                    ->maxLength(500)
                                    ->columnSpanFull()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, $component) {
                                        $length = strlen($state ?? '');
                                        $status = $length >= 150 && $length <= 160
                                            ? '✓ Perfect for SEO!'
                                            : ($length < 150
                                                ? '⚠️ Add more detail (' . (150 - $length) . ' more chars recommended)'
                                                : '⚠️ Too long (' . ($length - 160) . ' chars over limit)');
                                        $component->helperText("📊 {$length}/500 characters | {$status}");
                                    })
                                    ->helperText('📊 0/500 characters | ⚠️ Add more detail (150 more chars recommended)'),

                                Select::make('tags')
                                    ->multiple()
                                    ->relationship('tags', 'name')
                                    ->label('🏷️ Tags & Keywords')
                                    ->preload()
                                    ->helperText('Press Enter after each tag. Great for SEO and content organization.')
                                    ->columnSpanFull(),
                            ]),

                        TextEntry::make('seo_preview')
                            ->label('🔍 Google Search Preview')
                            ->state(function ($get) {
                                $title = $get('title') ?? 'Your Headline Here';
                                $description = $get('description') ?? 'Your meta description will appear here...';
                                $path = $get('slug_path') ?? '/category/your-post';

                                return new \Illuminate\Support\HtmlString("
                                    <div class='p-4 border-2 border-blue-200 rounded-lg bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-900 dark:border-blue-900'>
                                        <div class='mb-1 text-sm text-green-700 dark:text-green-400'>https://atannex.com/{$path}</div>
                                        <div class='mb-2 text-xl font-semibold text-blue-600 dark:text-blue-400'>{$title}</div>
                                        <div class='text-sm text-gray-600 dark:text-gray-400'>{$description}</div>
                                    </div>
                                ");
                            })
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull()
                    ->collapsible()
                    ->persistCollapsed()
                    ->extraAttributes(['class' => 'border-l-4 border-l-primary-500']),
                Grid::make(['default' => 1, 'lg' => 3])
                    ->schema([

                        Group::make()
                            ->schema([

                                Section::make('📂 Organization')
                                    ->description('Categorize and assign ownership')
                                    ->icon('heroicon-o-folder-open')
                                    ->iconColor('success')
                                    ->schema([
                                        Grid::make(2)
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
                                                                $emoji = $category->parent ? '└─' : '📁';
                                                                $label = $category->parent
                                                                    ? sprintf('%s %s → %s', $emoji, $category->parent->name, $category->name)
                                                                    : sprintf('%s %s', $emoji, $category->name);

                                                                return [$category->id => $label];
                                                            })
                                                            ->toArray();
                                                    })
                                                    ->createOptionForm([
                                                        Section::make('➕ Create New Category')
                                                            ->schema([
                                                                TextInput::make('name')
                                                                    ->label('Category Name')
                                                                    ->required()
                                                                    ->maxLength(255)
                                                                    ->placeholder('Enter category name')
                                                                    ->prefixIcon('heroicon-o-tag'),

                                                                Select::make('parent_id')
                                                                    ->label('Parent Category (optional)')
                                                                    ->options(
                                                                        Category::pluck('name', 'id')->toArray()
                                                                    )
                                                                    ->searchable()
                                                                    ->preload()
                                                                    ->placeholder('Select parent category')
                                                                    ->prefixIcon('heroicon-o-folder'),
                                                            ]),
                                                    ])
                                                    ->createOptionModalHeading('Create New Category')
                                                    ->createOptionAction(
                                                        fn($action) => $action
                                                            ->modalHeading('Create Category')
                                                            ->modalDescription('Add a new category or subcategory to organize your content.')
                                                            ->modalSubmitActionLabel('Create Category')
                                                            ->modalWidth('lg')
                                                            ->icon('heroicon-o-plus-circle')
                                                    )
                                                    ->helperText('Choose existing or create new')
                                                    ->native(false)
                                                    ->prefixIcon('heroicon-o-folder'),

                                                Select::make('region_id')
                                                    ->label('Region')
                                                    ->required()
                                                    ->searchable()
                                                    ->preload()
                                                    ->options(function () {
                                                        return Region::pluck('name', 'id')->toArray();
                                                    })
                                                    ->helperText('Geographic area')
                                                    ->prefixIcon('heroicon-o-map-pin'),
                                            ]),

                                        Select::make('author_id')
                                            ->relationship('author.user', 'name')
                                            ->label('✍️ Author')
                                            ->required()
                                            ->searchable()
                                            ->preload()
                                            ->default(fn() => Auth::id())
                                            ->prefixIcon('heroicon-o-user-circle')
                                            ->helperText('Content creator for this post')
                                            ->suffixIcon('heroicon-o-check-circle'),
                                    ])
                                    ->compact()
                                    ->collapsible()
                                    ->persistCollapsed(),

                                Section::make('📅 Publishing Schedule')
                                    ->description('Control when your content goes live')
                                    ->icon('heroicon-o-calendar-days')
                                    ->iconColor('warning')
                                    ->schema([
                                        DateTimePicker::make('published_at')
                                            ->label('🚀 Publish Date & Time')
                                            ->placeholder('Select publication date and time')
                                            ->helperText('Leave empty to publish immediately')
                                            ->native(false)
                                            ->displayFormat('M d, Y - H:i')
                                            ->seconds(false)
                                            ->prefixIcon('heroicon-o-clock'),

                                        TextEntry::make('publish_status')
                                            ->label('📊 Status Preview')
                                            ->state(function ($get, $record) {
                                                $publishAt = $get('published_at');

                                                if (!$publishAt) {
                                                    return new \Illuminate\Support\HtmlString("
                                                        <div class='px-3 py-2 font-semibold text-green-800 bg-green-100 rounded-lg dark:bg-green-900 dark:text-green-200'>
                                                            ✓ Will publish immediately on save
                                                        </div>
                                                    ");
                                                }

                                                $date = \Carbon\Carbon::parse($publishAt);
                                                $isFuture = $date->isFuture();

                                                $bgColor = $isFuture ? 'blue-100 dark:bg-blue-900' : 'green-100 dark:bg-green-900';
                                                $textColor = $isFuture ? 'blue-800 dark:text-blue-200' : 'green-800 dark:text-green-200';
                                                $icon = $isFuture ? '⏳' : '✓';
                                                $status = $isFuture ? 'Scheduled' : 'Published';

                                                return new \Illuminate\Support\HtmlString("
                                                    <div class='px-3 py-2 bg-{$bgColor} text-{$textColor} rounded-lg font-semibold'>
                                                        {$icon} {$status}: {$date->format('M d, Y - H:i')}
                                                        <div class='mt-1 text-xs opacity-75'>{$date->diffForHumans()}</div>
                                                    </div>
                                                ");
                                            }),

                                        TextEntry::make('updated_tracking')
                                            ->label('📝 Update History')
                                            ->state(function ($record) {
                                                if (!$record) {
                                                    return '🆕 Not yet created';
                                                }

                                                $updatedBy = $record->updatedBy->user->name ?? 'System';
                                                $updatedAt = $record->updated_at?->format('M d, Y - H:i') ?? 'N/A';

                                                return "Last modified by {$updatedBy} on {$updatedAt}";
                                            })
                                            ->helperText('Automatic tracking for audit compliance'),
                                    ])
                                    ->compact()
                                    ->collapsible()
                                    ->persistCollapsed(),
                            ])
                            ->columnSpan(['default' => 1, 'lg' => 2]),

                        Group::make()
                            ->schema([

                                Section::make('🖼️ Featured Media')
                                    ->description('Upload stunning visuals')
                                    ->icon('heroicon-o-photo')
                                    ->iconColor('danger')
                                    ->schema([
                                        FileUpload::make('image')
                                            ->label('Hero Image')
                                            ->disk('public')
                                            ->directory('posts')
                                            ->visibility('public')
                                            ->image()
                                            ->imageEditor()
                                            ->imageEditorAspectRatioOptions([
                                                '16:9' => '📺 16:9 (Widescreen - Recommended)',
                                                '4:3'  => '📱 4:3 (Standard)',
                                                '1:1'  => '⬛ 1:1 (Square - Instagram)',
                                                '21:9' => '🎬 21:9 (Cinematic)',
                                                '9:16' => '📱 9:16 (Stories)',
                                            ])
                                            ->maxSize(5120)
                                            ->acceptedFileTypes([
                                                'image/jpeg',
                                                'image/png',
                                                'image/webp',
                                                'image/gif',
                                                'image/jpg',
                                                'image/svg+xml',
                                            ])
                                            ->helperText('✨ Recommended: 1920×1080px | Max: 5MB | Formats: JPG, PNG, WebP')
                                            ->imagePreviewHeight(320)
                                            ->loadingIndicatorPosition('center')
                                            ->panelAspectRatio('16:9')
                                            ->panelLayout('integrated')
                                            ->removeUploadedFileButtonPosition('top-right')
                                            ->uploadProgressIndicatorPosition('center')
                                            ->columnSpanFull()
                                            ->afterStateUpdated(function ($state, $record) {
                                                if ($record && $record->image && $record->image !== $state) {
                                                    Storage::disk('public')->delete($record->image);
                                                }
                                            }),

                                        TextInput::make('image_alt')
                                            ->label('Alt Text (Accessibility)')
                                            ->placeholder('Describe the image for screen readers and SEO...')
                                            ->helperText('Improves accessibility and SEO')
                                            ->maxLength(255),
                                    ])
                                    ->compact()
                                    ->collapsible()
                                    ->persistCollapsed(),

                                Section::make("⭐ Editor's Pick")
                                    ->description('Curate premium content')
                                    ->icon('heroicon-o-star')
                                    ->iconColor('warning')
                                    ->schema([
                                        Toggle::make('is_editor_pick')
                                            ->label("✨ Feature as Editor's Pick")
                                            ->inline(false)
                                            ->helperText('Showcase in premium sections')
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

                                        TextEntry::make('editor_pick_badge')
                                            ->label('')
                                            ->state(function ($get) {
                                                if (!$get('is_editor_pick')) {
                                                    return null;
                                                }

                                                $expiresAt = $get('editor_pick_expires');
                                                $expires = $expiresAt ? \Carbon\Carbon::parse($expiresAt) : null;
                                                $remaining = $expires ? $expires->diffForHumans() : 'No expiration';

                                                return new \Illuminate\Support\HtmlString("
                                                    <div class='p-3 border-2 border-yellow-400 rounded-lg bg-gradient-to-r from-yellow-100 to-amber-100 dark:from-yellow-900 dark:to-amber-900 dark:border-yellow-600'>
                                                        <div class='flex items-center gap-2'>
                                                            <span class='text-2xl'>⭐</span>
                                                            <div>
                                                                <div class='font-bold text-yellow-900 dark:text-yellow-100'>Editor's Pick Active</div>
                                                                <div class='text-xs text-yellow-700 dark:text-yellow-300'>Expires {$remaining}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                ");
                                            })
                                            ->visible(fn($get) => $get('is_editor_pick')),

                                        Grid::make(2)
                                            ->schema([
                                                DateTimePicker::make('editor_pick_at')
                                                    ->label('Featured Since')
                                                    ->native(false)
                                                    ->displayFormat('M d, Y - H:i')
                                                    ->seconds(false)
                                                    ->helperText('Start date'),

                                                DateTimePicker::make('editor_pick_expires')
                                                    ->label('Expires At')
                                                    ->native(false)
                                                    ->displayFormat('M d, Y - H:i')
                                                    ->seconds(false)
                                                    ->helperText('Auto-remove after')
                                                    ->minDate(fn($get) => $get('editor_pick_at')),
                                            ])
                                            ->visible(fn($get) => $get('is_editor_pick')),
                                    ])
                                    ->compact()
                                    ->collapsed()
                                    ->persistCollapsed(),

                                Section::make('⚡ Breaking News')
                                    ->description('Urgent, time-sensitive content')
                                    ->icon('heroicon-o-bolt')
                                    ->iconColor('danger')
                                    ->schema([
                                        Toggle::make('is_breaking')
                                            ->label('🚨 Mark as Breaking News')
                                            ->inline(false)
                                            ->helperText('Display with priority placement')
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

                                        TextEntry::make('breaking_badge')
                                            ->label('')
                                            ->state(function ($get) {
                                                if (!$get('is_breaking')) {
                                                    return null;
                                                }

                                                $expiresAt = $get('breaking_expires');
                                                $expires = $expiresAt ? \Carbon\Carbon::parse($expiresAt) : null;
                                                $remaining = $expires ? $expires->diffForHumans() : 'No expiration';

                                                return new \Illuminate\Support\HtmlString("
                                                    <div class='p-3 border-2 border-red-500 rounded-lg bg-gradient-to-r from-red-100 to-orange-100 dark:from-red-900 dark:to-orange-900 dark:border-red-600 animate-pulse'>
                                                        <div class='flex items-center gap-2'>
                                                            <span class='text-2xl'>🚨</span>
                                                            <div>
                                                                <div class='font-bold text-red-900 dark:text-red-100'>BREAKING NEWS ACTIVE</div>
                                                                <div class='text-xs text-red-700 dark:text-red-300'>Expires {$remaining}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                ");
                                            })
                                            ->visible(fn($get) => $get('is_breaking')),

                                        Grid::make(2)
                                            ->schema([
                                                DateTimePicker::make('breaking_at')
                                                    ->label('Breaking Since')
                                                    ->native(false)
                                                    ->displayFormat('M d, Y - H:i')
                                                    ->seconds(false)
                                                    ->helperText('When it broke'),

                                                DateTimePicker::make('breaking_expires')
                                                    ->label('Expires At')
                                                    ->native(false)
                                                    ->displayFormat('M d, Y - H:i')
                                                    ->seconds(false)
                                                    ->helperText('Auto-remove after')
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
