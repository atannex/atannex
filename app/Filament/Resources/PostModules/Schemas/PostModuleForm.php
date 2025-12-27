<?php

namespace App\Filament\Resources\PostModules\Schemas;

use App\Enums\PostType;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Radio;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class PostModuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Tabs::make('PostConfiguration')
                ->tabs([

                    Tabs\Tab::make('Basic Information')
                        ->icon('heroicon-o-document-text')
                        ->schema([
                            Section::make('Post Configuration')
                                ->description('Configure the basic settings and metadata for this post module')
                                ->icon('heroicon-o-cog-6-tooth')
                                ->schema([
                                    Grid::make(1)->schema([
                                        Select::make('post_id')
                                            ->relationship('post', 'title')
                                            ->searchable()
                                            ->preload()
                                            ->placeholder('Select a post')
                                            ->helperText('Link this module to an existing post')
                                            ->required()
                                            ->columnSpan(1),
                                    ]),

                                    Grid::make(2)->schema([
                                        DateTimePicker::make('published_at')
                                            ->label('Publication Date')
                                            ->placeholder('Schedule publication')
                                            ->helperText('Leave empty for immediate publication')
                                            ->native(false)
                                            ->displayFormat('M d, Y H:i')
                                            ->columnSpan(1),

                                        Select::make('type')
                                            ->label('Post Type')
                                            ->options(PostType::asSelectArray())
                                            ->default(PostType::ARTICLE)
                                            ->preload()
                                            ->searchable()
                                            ->required()
                                            ->helperText('Choose the content type')
                                            ->columnSpan(1),
                                    ]),
                                ])
                                ->collapsible()
                                ->persistCollapsed()
                                ->compact(),

                            Section::make('SEO & Metadata')
                                ->description('Optimize your post for search engines and social sharing')
                                ->icon('heroicon-o-magnifying-glass')
                                ->schema([
                                    Grid::make(1)->schema([
                                        TextInput::make('meta_title')
                                            ->label('Meta Title')
                                            ->placeholder('SEO optimized title (50-60 characters)')
                                            ->maxLength(60)
                                            ->helperText('Recommended: 50-60 characters')
                                            ->columnSpanFull(),

                                        Textarea::make('meta_description')
                                            ->label('Meta Description')
                                            ->placeholder('Brief description for search results (150-160 characters)')
                                            ->maxLength(160)
                                            ->rows(3)
                                            ->helperText('Recommended: 150-160 characters')
                                            ->columnSpanFull(),

                                        TagsInput::make('keywords')
                                            ->label('Focus Keywords')
                                            ->placeholder('Add keywords')
                                            ->helperText('SEO keywords for this post')
                                            ->separator(',')
                                            ->columnSpanFull(),
                                    ]),
                                ])
                                ->collapsible()
                                ->collapsed()
                                ->compact(),
                        ]),

                    Tabs\Tab::make('Upload Video')
                        ->icon('heroicon-o-video-camera')
                        ->schema([
                            Section::make('Primary Video Configuration')
                                ->description('Upload and configure the main video for this post')
                                ->icon('heroicon-o-play-circle')
                                ->schema([
                                    Grid::make(3)
                                        ->schema([
                                            Grid::make(1)
                                                ->schema([
                                                    FileUpload::make('video.path')
                                                        ->label('Video File')
                                                        ->disk('public')
                                                        ->visibility('public')
                                                        ->directory('videos/blog')
                                                        ->acceptedFileTypes([
                                                            'video/mp4',
                                                            'video/webm',
                                                            'video/ogg',
                                                        ])
                                                        ->maxSize(51200)
                                                        ->helperText('Supported formats: MP4, WebM, OGG (max 50MB)'),
                                                ])
                                                ->columnSpan(2),

                                            Grid::make(1)
                                                ->schema([
                                                    FileUpload::make('video.poster')
                                                        ->label('Poster Image')
                                                        ->disk('public')
                                                        ->directory('videos/posters')
                                                        ->visibility('public')
                                                        ->image()
                                                        ->imageEditor()
                                                        ->imagePreviewHeight('200')
                                                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                                        ->maxSize(2048)
                                                        ->helperText('Displayed before video plays (optional, max 2MB)'),

                                                    TextInput::make('video.mime')
                                                        ->label('MIME Type')
                                                        ->default('video/mp4')
                                                        ->dehydrated()
                                                        ->disabled()
                                                        ->helperText('Usually auto-detected (video/mp4)'),

                                                    TextInput::make('video.duration')
                                                        ->label('Duration')
                                                        ->placeholder('10:30')
                                                        ->disabled()
                                                        ->helperText('Optional display value (mm:ss)')
                                                        ->mask('99:99'),
                                                ])
                                                ->columnSpan(1),
                                        ]),
                                ])
                                ->collapsible()
                                ->persistCollapsed()
                                ->compact(),
                        ]),

                    Tabs\Tab::make('Featured Video')
                        ->icon('heroicon-o-video-camera')
                        ->schema([
                            Section::make('Primary Video Configuration')
                                ->description('Set up the main video for this post')
                                ->icon('heroicon-o-play-circle')
                                ->schema([
                                    Grid::make(3)
                                        ->schema([
                                            Grid::make(1)
                                                ->schema([
                                                    TextInput::make('video.id')
                                                        ->label('YouTube Video ID')
                                                        ->placeholder('e.g. 3HP0typOiVY')
                                                        ->default('')
                                                        ->dehydrated()
                                                        ->prefix('youtube.com/watch?v=')
                                                        ->helperText('The unique ID from the YouTube URL'),

                                                    TextInput::make('video.signature')
                                                        ->label('YouTube Signature')
                                                        ->placeholder('e.g. jNoRG1I7lZ_eJnLp')
                                                        ->default('')
                                                        ->dehydrated()
                                                        ->helperText('Required for embedded playback'),

                                                    TextInput::make('video.duration')
                                                        ->label('Duration')
                                                        ->placeholder('10:30')
                                                        ->disabled()
                                                        ->helperText('Video length (mm:ss)')
                                                        ->mask('99:99'),
                                                ])
                                                ->columnSpan(2),

                                            Grid::make(1)
                                                ->schema([
                                                    FileUpload::make('video.thumbnail')
                                                        ->label('Custom Thumbnail')
                                                        ->image()
                                                        ->disk('public')
                                                        ->visibility('public')
                                                        ->directory('videos/thumbnails')
                                                        ->imageEditor()
                                                        ->imagePreviewHeight('200')
                                                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                                        ->maxSize(2048)
                                                        ->helperText('Optional: Override YouTube thumbnail (max 2MB)'),
                                                ])
                                                ->columnSpan(1),
                                        ]),
                                ])
                                ->collapsible()
                                ->persistCollapsed()
                                ->compact(),
                        ]),
                    Tabs\Tab::make('Content Builder')
                        ->icon('heroicon-o-building-library')
                        ->badge(fn($state) => count($state['content'] ?? []))
                        ->schema([
                            Section::make('Module Content')
                                ->description('Build your post content using flexible, drag-and-drop content blocks')
                                ->icon('heroicon-o-squares-plus')
                                ->schema([
                                    Builder::make('content')
                                        ->columnSpanFull()
                                        ->blocks([
                                            // VIDEO GRID BLOCK
                                            Block::make('video_grid')
                                                ->label('Video Grid')
                                                ->icon('heroicon-o-film')
                                                ->schema([
                                                    Repeater::make('items')
                                                        ->label('Videos')
                                                        ->schema([
                                                            Grid::make(3)->schema([
                                                                // Left - Cover Image
                                                                FileUpload::make('cover')
                                                                    ->label('Cover Image')
                                                                    ->image()
                                                                    ->disk('public')
                                                                    ->visibility('public')
                                                                    ->directory('videos/covers')
                                                                    ->imageEditor()
                                                                    ->imageEditorAspectRatios([
                                                                        '16:9' => '16:9 (YouTube Standard)',
                                                                    ])
                                                                    ->imagePreviewHeight('150')
                                                                    ->required()
                                                                    ->columnSpan(1),

                                                                // Right - Video Details
                                                                Grid::make(1)->schema([
                                                                    TextInput::make('video.id')
                                                                        ->label('YouTube Video ID')
                                                                        ->required()
                                                                        ->placeholder('e.g. 3HP0typOiVY'),

                                                                    TextInput::make('video.signature')
                                                                        ->label('YouTube Signature')
                                                                        ->required()
                                                                        ->placeholder('e.g. jNoRG1I7lZ_eJnLp'),
                                                                ])->columnSpan(2),
                                                            ]),
                                                        ])
                                                        ->columns(1)
                                                        ->defaultItems(3)
                                                        ->minItems(1)
                                                        ->maxItems(12)
                                                        ->collapsible()
                                                        ->itemLabel(fn(array $state): ?string => $state['title'] ?? 'Video Item')
                                                        ->addActionLabel('Add Video')
                                                        ->reorderable()
                                                        ->cloneable(),
                                                ]),

                                            // SINGLE VIDEO BLOCK
                                            Block::make('video')
                                                ->label('Video Block')
                                                ->icon('heroicon-o-video-camera')
                                                ->schema([
                                                    Grid::make(3)->schema([
                                                        // Left - Cover Image
                                                        FileUpload::make('cover')
                                                            ->label('Cover Image')
                                                            ->image()
                                                            ->disk('public')
                                                            ->visibility('public')
                                                            ->directory('videos/covers')
                                                            ->imageEditor()
                                                            ->imageEditorAspectRatios([
                                                                '16:9' => '16:9 (Recommended)',
                                                            ])
                                                            ->imagePreviewHeight('200')
                                                            ->required()
                                                            ->columnSpan(1),

                                                        // Right - Video Details
                                                        Grid::make(1)->schema([
                                                            TextInput::make('video.id')
                                                                ->label('YouTube Video ID')
                                                                ->placeholder('3HP0typOiVY')
                                                                ->required(),

                                                            TextInput::make('video.signature')
                                                                ->label('YouTube Signature')
                                                                ->placeholder('jNoRG1I7lZ_eJnLp')
                                                                ->required(),
                                                        ])->columnSpan(2),
                                                    ]),
                                                ]),

                                            // TEXT CONTENT BLOCK
                                            Block::make('paragraphs')
                                                ->label('Text Content')
                                                ->icon('heroicon-o-document-text')
                                                ->schema([
                                                    Repeater::make('content')
                                                        ->label('Paragraphs')
                                                        ->schema([
                                                            RichEditor::make('value')
                                                                ->label('Paragraph')
                                                                ->placeholder('Write your content here...')
                                                                ->toolbarButtons([
                                                                    'bold',
                                                                    'italic',
                                                                    'underline',
                                                                    'link',
                                                                    'bulletList',
                                                                    'orderedList',
                                                                    'h2',
                                                                    'h3',
                                                                    'blockquote',
                                                                    'codeBlock',
                                                                ])
                                                                ->columnSpanFull(),
                                                        ])
                                                        ->addActionLabel('Add Paragraph')
                                                        ->collapsible()
                                                        ->cloneable()
                                                        ->reorderable()
                                                        ->itemLabel(fn(array $state): ?string => str($state['value'] ?? '')->stripTags()->limit(50))
                                                        ->columnSpanFull(),
                                                ]),

                                            // HEADING BLOCK
                                            Block::make('heading')
                                                ->label('Heading')
                                                ->icon('heroicon-o-bars-3-bottom-left')
                                                ->schema([
                                                    Grid::make(3)->schema([
                                                        // Left - Main Heading Input
                                                        TextInput::make('title')
                                                            ->label('Heading Text')
                                                            ->required()
                                                            ->placeholder('Enter heading...')
                                                            ->columnSpan(2),

                                                        // Right - Settings
                                                        Grid::make(1)->schema([
                                                            Select::make('level')
                                                                ->label('Heading Level')
                                                                ->options([
                                                                    'h1' => 'H1 - Main',
                                                                    'h2' => 'H2 - Section',
                                                                    'h3' => 'H3 - Subsection',
                                                                    'h4' => 'H4 - Minor',
                                                                ])
                                                                ->default('h2'),

                                                            Radio::make('alignment')
                                                                ->label('Alignment')
                                                                ->options([
                                                                    'left' => 'Left',
                                                                    'center' => 'Center',
                                                                    'right' => 'Right',
                                                                ])
                                                                ->default('left')
                                                                ->inline(),

                                                            ColorPicker::make('color')
                                                                ->label('Text Color')
                                                                ->helperText('Optional custom color'),
                                                        ])->columnSpan(1),
                                                    ]),
                                                ]),

                                            // IMAGE BLOCK
                                            Block::make('image')
                                                ->label('Image')
                                                ->icon('heroicon-o-photo')
                                                ->schema([
                                                    Grid::make(3)->schema([
                                                        // Left - Image Upload
                                                        FileUpload::make('src')
                                                            ->label('Image File')
                                                            ->disk('public')
                                                            ->visibility('public')
                                                            ->directory('posts/images')
                                                            ->image()
                                                            ->imageEditor()
                                                            ->imageEditorAspectRatios([
                                                                null => 'Free',
                                                                '16:9' => '16:9 (Landscape)',
                                                                '4:3' => '4:3 (Standard)',
                                                                '1:1' => '1:1 (Square)',
                                                                '9:16' => '9:16 (Portrait)',
                                                            ])
                                                            ->imagePreviewHeight('250')
                                                            ->maxSize(5120)
                                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                                            ->helperText('Formats: JPG, PNG, WebP | Max: 5MB')
                                                            ->required()
                                                            ->columnSpan(1),

                                                        // Right - Image Settings
                                                        Grid::make(1)->schema([
                                                            TextInput::make('alt')
                                                                ->label('Alt Text')
                                                                ->placeholder('Describe the image for accessibility')
                                                                ->helperText('Important for SEO and accessibility'),

                                                            TextInput::make('caption')
                                                                ->label('Caption')
                                                                ->placeholder('Optional caption below image'),

                                                            Radio::make('size')
                                                                ->label('Display Size')
                                                                ->options([
                                                                    'small' => 'Small (400px)',
                                                                    'medium' => 'Medium (800px)',
                                                                    'large' => 'Large (1200px)',
                                                                    'full' => 'Full Width',
                                                                ])
                                                                ->default('large')
                                                                ->inline(),

                                                            Radio::make('alignment')
                                                                ->label('Alignment')
                                                                ->options([
                                                                    'left' => 'Left',
                                                                    'center' => 'Center',
                                                                    'right' => 'Right',
                                                                ])
                                                                ->default('center')
                                                                ->inline(),
                                                        ])->columnSpan(2),
                                                    ]),
                                                ]),

                                            // AD BANNER BLOCK
                                            Block::make('ad-banner')
                                                ->label('Advertisement Banner')
                                                ->icon('heroicon-o-megaphone')
                                                ->schema([
                                                    Grid::make(2)->schema([
                                                        TextInput::make('href')
                                                            ->label('Target URL')
                                                            ->url()
                                                            ->placeholder('https://example.com')
                                                            ->helperText('Where should this banner link?')
                                                            ->columnSpan(2),

                                                        Toggle::make('opens_new_tab')
                                                            ->label('Open in New Tab')
                                                            ->default(true)
                                                            ->inline(false)
                                                            ->columnSpan(1),

                                                        TextInput::make('tracking_id')
                                                            ->label('Tracking ID')
                                                            ->placeholder('Optional analytics ID')
                                                            ->columnSpan(1),
                                                    ]),

                                                    Repeater::make('images')
                                                        ->label('Theme Variants')
                                                        ->schema([
                                                            Grid::make(3)->schema([
                                                                // Left - Banner Image
                                                                FileUpload::make('path')
                                                                    ->label('Banner Image')
                                                                    ->image()
                                                                    ->disk('public')
                                                                    ->visibility('public')
                                                                    ->directory('posts/banners')
                                                                    ->imageEditor()
                                                                    ->imageEditorAspectRatios([
                                                                        '16:9' => '16:9 (Recommended)',
                                                                        '4:3' => '4:3 (Standard)',
                                                                        '21:9' => '21:9 (Ultra-wide)',
                                                                    ])
                                                                    ->maxSize(5120)
                                                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                                                    ->helperText('Recommended: 1200x675px (16:9)')
                                                                    ->imagePreviewHeight('150')
                                                                    ->required()
                                                                    ->columnSpan(2),

                                                                // Right - Theme Selection
                                                                Select::make('theme')
                                                                    ->label('Theme')
                                                                    ->options([
                                                                        'light' => 'Light Mode',
                                                                        'dark' => 'Dark Mode',
                                                                        'auto' => 'Auto (Both)',
                                                                    ])
                                                                    ->default('auto')
                                                                    ->required()
                                                                    ->columnSpan(1),
                                                            ]),
                                                        ])
                                                        ->addActionLabel('Add Theme Variant')
                                                        ->itemLabel(fn(array $state): ?string => ucfirst($state['theme'] ?? 'Banner') . ' Banner')
                                                        ->collapsible()
                                                        ->minItems(1)
                                                        ->maxItems(3)
                                                        ->columnSpanFull(),
                                                ]),

                                            // BLOCKQUOTE BLOCK
                                            Block::make('blockquote')
                                                ->label('Quote Block')
                                                ->icon('heroicon-o-chat-bubble-left-ellipsis')
                                                ->schema([
                                                    Textarea::make('quote')
                                                        ->label('Quote Text')
                                                        ->required()
                                                        ->rows(4)
                                                        ->placeholder('Enter the quote text...')
                                                        ->columnSpanFull(),

                                                    Grid::make(3)->schema([
                                                        TextInput::make('author')
                                                            ->label('Author Name')
                                                            ->placeholder('e.g. John Doe')
                                                            ->columnSpan(1),

                                                        TextInput::make('author_title')
                                                            ->label('Author Title/Role')
                                                            ->placeholder('e.g. CEO, Company Name')
                                                            ->columnSpan(1),

                                                        Radio::make('style')
                                                            ->label('Quote Style')
                                                            ->options([
                                                                'default' => 'Default',
                                                                'highlighted' => 'Highlighted',
                                                                'minimal' => 'Minimal',
                                                            ])
                                                            ->default('default')
                                                            ->inline()
                                                            ->columnSpan(1),
                                                    ]),
                                                ]),

                                            // SIDE-BY-SIDE BLOCK
                                            Block::make('side-by-side')
                                                ->label('Side-by-Side Layout')
                                                ->icon('heroicon-o-rectangle-group')
                                                ->schema([
                                                    Radio::make('image_position')
                                                        ->label('Image Position')
                                                        ->options([
                                                            'left' => 'Image on Left',
                                                            'right' => 'Image on Right',
                                                        ])
                                                        ->default('left')
                                                        ->inline()
                                                        ->columnSpanFull(),

                                                    Grid::make(3)->schema([
                                                        // Left/Right - Image
                                                        FileUpload::make('image')
                                                            ->label('Featured Image')
                                                            ->disk('public')
                                                            ->visibility('public')
                                                            ->directory('posts/images')
                                                            ->image()
                                                            ->imageEditor()
                                                            ->imageEditorAspectRatios([
                                                                '1:1' => '1:1 (Square)',
                                                                '4:3' => '4:3 (Standard)',
                                                                '16:9' => '16:9 (Wide)',
                                                            ])
                                                            ->imagePreviewHeight('200')
                                                            ->maxSize(5120)
                                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                                            ->required()
                                                            ->columnSpan(1),

                                                        // Right/Left - Content
                                                        Grid::make(1)->schema([
                                                            TextInput::make('heading')
                                                                ->label('Section Heading')
                                                                ->placeholder('Enter heading...'),

                                                            Textarea::make('paragraph')
                                                                ->label('Description')
                                                                ->rows(4)
                                                                ->placeholder('Enter description...'),
                                                        ])->columnSpan(2),
                                                    ]),

                                                    Repeater::make('list')
                                                        ->label('Key Points / Features')
                                                        ->schema([
                                                            Grid::make(4)->schema([
                                                                TextInput::make('value')
                                                                    ->label('Point')
                                                                    ->placeholder('Enter bullet point...')
                                                                    ->required()
                                                                    ->columnSpan(3),

                                                                Select::make('icon')
                                                                    ->label('Icon')
                                                                    ->options([
                                                                        'check' => '✓ Check',
                                                                        'star' => '★ Star',
                                                                        'arrow' => '→ Arrow',
                                                                        'dot' => '• Dot',
                                                                    ])
                                                                    ->default('check')
                                                                    ->columnSpan(1),
                                                            ]),
                                                        ])
                                                        ->itemLabel(fn(array $state): ?string => '• ' . str($state['value'] ?? '')->limit(50))
                                                        ->addActionLabel('Add Bullet Point')
                                                        ->collapsible()
                                                        ->reorderable()
                                                        ->cloneable()
                                                        ->columnSpanFull(),
                                                ]),

                                            // CODE SNIPPET BLOCK
                                            Block::make('code')
                                                ->label('Code Snippet')
                                                ->icon('heroicon-o-code-bracket')
                                                ->schema([
                                                    Grid::make(3)->schema([
                                                        // Left - Code Content
                                                        Textarea::make('code')
                                                            ->label('Code Content')
                                                            ->placeholder('Paste your code here...')
                                                            ->rows(10)
                                                            ->required()
                                                            ->columnSpan(2),

                                                        // Right - Code Settings
                                                        Grid::make(1)->schema([
                                                            TextInput::make('title')
                                                                ->label('Code Title')
                                                                ->placeholder('e.g. Example Implementation'),

                                                            Select::make('language')
                                                                ->label('Language')
                                                                ->options([
                                                                    'php' => 'PHP',
                                                                    'javascript' => 'JavaScript',
                                                                    'python' => 'Python',
                                                                    'html' => 'HTML',
                                                                    'css' => 'CSS',
                                                                    'sql' => 'SQL',
                                                                    'bash' => 'Bash',
                                                                    'json' => 'JSON',
                                                                ])
                                                                ->default('php')
                                                                ->searchable(),

                                                            Toggle::make('show_line_numbers')
                                                                ->label('Show Line Numbers')
                                                                ->default(true)
                                                                ->inline(false),

                                                            Toggle::make('highlightable')
                                                                ->label('Enable Syntax Highlighting')
                                                                ->default(true)
                                                                ->inline(false),
                                                        ])->columnSpan(1),
                                                    ]),
                                                ]),

                                            // DATA TABLE BLOCK
                                            Block::make('table')
                                                ->label('Data Table')
                                                ->icon('heroicon-o-table-cells')
                                                ->schema([
                                                    TextInput::make('title')
                                                        ->label('Table Title')
                                                        ->placeholder('Optional table caption')
                                                        ->columnSpanFull(),

                                                    Repeater::make('headers')
                                                        ->label('Table Headers')
                                                        ->schema([
                                                            TextInput::make('label')
                                                                ->label('Header Label')
                                                                ->required()
                                                                ->placeholder('Column name'),
                                                        ])
                                                        ->addActionLabel('Add Column')
                                                        ->collapsible()
                                                        ->grid(4)
                                                        ->columnSpanFull(),

                                                    Repeater::make('rows')
                                                        ->label('Table Rows')
                                                        ->schema([
                                                            Repeater::make('cells')
                                                                ->label('Row Data')
                                                                ->schema([
                                                                    TextInput::make('value')
                                                                        ->label('Cell Value')
                                                                        ->placeholder('Enter value'),
                                                                ])
                                                                ->grid(4)
                                                                ->columnSpanFull(),
                                                        ])
                                                        ->addActionLabel('Add Row')
                                                        ->collapsible()
                                                        ->columnSpanFull(),

                                                    Toggle::make('striped')
                                                        ->label('Striped Rows')
                                                        ->default(true)
                                                        ->inline(false)
                                                        ->columnSpanFull(),
                                                ]),

                                            // CALL-TO-ACTION BLOCK
                                            Block::make('cta')
                                                ->label('Call-to-Action')
                                                ->icon('heroicon-o-cursor-arrow-rays')
                                                ->schema([
                                                    Grid::make(3)->schema([
                                                        // Left - CTA Content
                                                        Grid::make(1)->schema([
                                                            TextInput::make('heading')
                                                                ->label('CTA Heading')
                                                                ->placeholder('Ready to get started?')
                                                                ->required(),

                                                            Textarea::make('description')
                                                                ->label('Description')
                                                                ->placeholder('Brief description or subheading')
                                                                ->rows(2),
                                                        ])->columnSpan(2),

                                                        // Right - CTA Settings
                                                        Grid::make(1)->schema([
                                                            TextInput::make('button_text')
                                                                ->label('Button Text')
                                                                ->placeholder('Get Started')
                                                                ->required(),

                                                            TextInput::make('button_url')
                                                                ->label('Button URL')
                                                                ->url()
                                                                ->placeholder('https://example.com')
                                                                ->required(),

                                                            Select::make('style')
                                                                ->label('CTA Style')
                                                                ->options([
                                                                    'primary' => 'Primary (Bold)',
                                                                    'secondary' => 'Secondary',
                                                                    'gradient' => 'Gradient',
                                                                    'outline' => 'Outline',
                                                                ])
                                                                ->default('primary'),

                                                            ColorPicker::make('background_color')
                                                                ->label('Background Color')
                                                                ->helperText('Optional custom background'),
                                                        ])->columnSpan(1),
                                                    ]),
                                                ]),

                                            // DIVIDER BLOCK
                                            Block::make('divider')
                                                ->label('Divider / Spacer')
                                                ->icon('heroicon-o-minus')
                                                ->schema([
                                                    Grid::make(3)->schema([
                                                        Select::make('style')
                                                            ->label('Divider Style')
                                                            ->options([
                                                                'line' => 'Horizontal Line',
                                                                'dashed' => 'Dashed Line',
                                                                'dotted' => 'Dotted Line',
                                                                'space' => 'Empty Space',
                                                            ])
                                                            ->default('line')
                                                            ->columnSpan(1),

                                                        Select::make('spacing')
                                                            ->label('Spacing')
                                                            ->options([
                                                                'small' => 'Small (20px)',
                                                                'medium' => 'Medium (40px)',
                                                                'large' => 'Large (60px)',
                                                            ])
                                                            ->default('medium')
                                                            ->columnSpan(1),

                                                        ColorPicker::make('color')
                                                            ->label('Line Color')
                                                            ->helperText('Optional custom color')
                                                            ->columnSpan(1),
                                                    ]),
                                                ]),
                                        ])
                                        ->blockNumbers(false)
                                        ->addActionLabel('+ Add Content Block')
                                        ->collapsible()
                                        ->cloneable()
                                        ->reorderable()
                                        ->blockPickerColumns(3)
                                        ->blockPickerWidth('2xl')
                                        ->columnSpanFull(),
                                ])
                                ->collapsible()
                                ->persistCollapsed()
                                ->compact(),
                        ]),
                ])
                ->columnSpanFull()
                ->persistTabInQueryString()
                ->contained(false),
        ]);
    }
}
