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
use Filament\Schemas\Components\Fieldset;

class PostModuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Tabs::make('PostConfiguration')
                ->tabs([

                    // ========================================
                    // TAB 1: BASIC INFORMATION
                    // ========================================
                    Tabs\Tab::make('Basic Information')
                        ->icon('heroicon-o-document-text')
                        ->schema([
                            Section::make('Post Configuration')
                                ->description('Configure essential settings and metadata for this post module')
                                ->icon('heroicon-o-cog-6-tooth')
                                ->schema([
                                    Grid::make(2)
                                        ->schema([
                                            // Post Association
                                            Fieldset::make('Post Association')
                                                ->schema([
                                                    Select::make('post_id')
                                                        ->label('Associated Post')
                                                        ->relationship('post', 'title')
                                                        ->searchable()
                                                        ->preload()
                                                        ->placeholder('Search and select an existing post')
                                                        ->helperText('Connect this module to an existing post in your system')
                                                        ->required()
                                                        ->native(false)
                                                        ->columnSpanFull(),
                                                ])
                                                ->columnSpan(1),

                                            // Content Classification
                                            Fieldset::make('Content Classification')
                                                ->schema([
                                                    Select::make('type')
                                                        ->label('Content Type')
                                                        ->options(PostType::asSelectArray())
                                                        ->default(PostType::ARTICLE)
                                                        ->preload()
                                                        ->searchable()
                                                        ->required()
                                                        ->native(false)
                                                        ->helperText('Defines how this content will be displayed and categorized')
                                                        ->columnSpanFull(),
                                                ])
                                                ->columnSpan(1),
                                        ]),
                                ])
                                ->collapsible()
                                ->persistCollapsed(),
                        ]),

                    // ========================================
                    // TAB 2: UPLOAD VIDEO
                    // ========================================
                    Tabs\Tab::make('Upload Video')
                        ->icon('heroicon-o-video-camera')
                        ->schema([
                            Section::make('Primary Video Upload')
                                ->description('Upload and configure a video file to be hosted directly on your platform')
                                ->icon('heroicon-o-play-circle')
                                ->schema([
                                    Grid::make(2)
                                        ->schema([
                                            // Video File Upload
                                            Fieldset::make('Video File')
                                                ->schema([
                                                    FileUpload::make('video.path')
                                                        ->label('Video File')
                                                        ->disk('public')
                                                        ->visibility('public')
                                                        ->directory(fn($record) => $record?->dir() ?? 'post_module_path')
                                                        ->acceptedFileTypes([
                                                            'video/mp4',
                                                            'video/webm',
                                                            'video/ogg',
                                                        ])
                                                        ->maxSize(51200)
                                                        ->helperText('Supported formats: MP4, WebM, OGG • Maximum size: 50MB')
                                                        ->imagePreviewHeight('300')
                                                        ->panelLayout('integrated')
                                                        ->panelAspectRatio('16:9')
                                                        ->columnSpanFull(),
                                                ])
                                                ->columns(1)
                                                ->columnSpan(1),

                                            // Video Poster
                                            Fieldset::make('Video Poster')
                                                ->schema([
                                                    FileUpload::make('video.poster')
                                                        ->label('Poster Image')
                                                        ->disk('public')
                                                        ->directory(fn($record) => $record?->dir() ?? 'posts/posters')
                                                        ->visibility('public')
                                                        ->image()
                                                        ->imageEditor()
                                                        ->imageEditorAspectRatios([
                                                            '16:9' => '16:9 (Recommended)',
                                                            '4:3' => '4:3 (Standard)',
                                                            '1:1' => '1:1 (Square)',
                                                        ])
                                                        ->imagePreviewHeight('300')
                                                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                                        ->maxSize(2048)
                                                        ->helperText('Thumbnail displayed before playback • Optional • Maximum: 2MB')
                                                        ->panelLayout('integrated')
                                                        ->columnSpanFull(),
                                                ])
                                                ->columns(1)
                                                ->columnSpan(1),
                                        ]),
                                ])
                                ->collapsible()
                                ->persistCollapsed(),
                        ]),

                    // ========================================
                    // TAB 3: FEATURED VIDEO (YOUTUBE)
                    // ========================================
                    Tabs\Tab::make('Featured Video')
                        ->icon('heroicon-o-film')
                        ->schema([
                            Section::make('YouTube Video Integration')
                                ->description('Embed a YouTube video as the featured content for this post')
                                ->icon('heroicon-o-play-circle')
                                ->schema([
                                    Grid::make(2)
                                        ->schema([
                                            // YouTube Details
                                            Fieldset::make('YouTube Video Details')
                                                ->schema([
                                                    TextInput::make('video.id')
                                                        ->label('YouTube Video ID')
                                                        ->placeholder('dQw4w9WgXcQ')
                                                        ->default('')
                                                        ->dehydrated()
                                                        ->prefix('youtube.com/watch?v=')
                                                        ->suffixIcon('heroicon-o-link')
                                                        ->helperText('Extract the ID from the YouTube URL (e.g., youtube.com/watch?v=VIDEO_ID)')
                                                        ->columnSpanFull(),

                                                    TextInput::make('video.signature')
                                                        ->label('YouTube Signature')
                                                        ->placeholder('jNoRG1I7lZ_eJnLp')
                                                        ->default('')
                                                        ->dehydrated()
                                                        ->suffixIcon('heroicon-o-key')
                                                        ->helperText('Required for enhanced embedded playback features')
                                                        ->columnSpanFull(),
                                                ])
                                                ->columns(1)
                                                ->columnSpan(1),

                                            // Custom Thumbnail
                                            Fieldset::make('Custom Thumbnail')
                                                ->schema([
                                                    FileUpload::make('video.thumbnail')
                                                        ->label('Thumbnail Override')
                                                        ->image()
                                                        ->disk('public')
                                                        ->visibility('public')
                                                        ->directory(fn($record) => $record?->dir() ?? 'videos/thumbnails')
                                                        ->imageEditor()
                                                        ->imageEditorAspectRatios([
                                                            '16:9' => '16:9 (YouTube Standard)',
                                                        ])
                                                        ->imagePreviewHeight('280')
                                                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                                        ->maxSize(2048)
                                                        ->helperText('Optional: Replace YouTube\'s default thumbnail • Maximum: 2MB')
                                                        ->panelLayout('integrated')
                                                        ->columnSpanFull(),
                                                ])
                                                ->columns(1)
                                                ->columnSpan(1),
                                        ]),
                                ])
                                ->collapsible()
                                ->persistCollapsed(),
                        ]),

                    // ========================================
                    // TAB 4: CONTENT BUILDER
                    // ========================================
                    Tabs\Tab::make('Content Builder')
                        ->icon('heroicon-o-building-library')
                        ->badge(fn($state) => count($state['content'] ?? []))
                        ->badgeColor('primary')
                        ->schema([
                            Section::make('Build Your Content')
                                ->description('Compose your post using flexible, drag-and-drop content blocks. Mix and match different block types to create engaging layouts.')
                                ->icon('heroicon-o-squares-plus')
                                ->schema([
                                    Builder::make('content')
                                        ->columnSpanFull()
                                        ->blocks([

                                            // ========================================
                                            // BLOCK: VIDEO GRID
                                            // ========================================
                                            Block::make('video_grid')
                                                ->label('Video Grid')
                                                ->icon('heroicon-o-film')
                                                ->schema([
                                                    Repeater::make('items')
                                                        ->label('Video Collection')
                                                        ->schema([
                                                            Grid::make(2)
                                                                ->schema([
                                                                    // Thumbnail
                                                                    Fieldset::make('Thumbnail')
                                                                        ->schema([
                                                                            FileUpload::make('cover')
                                                                                ->label('Cover Image')
                                                                                ->image()
                                                                                ->disk('public')
                                                                                ->visibility('public')
                                                                                ->directory(fn($record) => $record?->dir() ?? 'videos/covers')
                                                                                ->imageEditor()
                                                                                ->imageEditorAspectRatios([
                                                                                    '16:9' => '16:9 (YouTube Standard)',
                                                                                ])
                                                                                ->imagePreviewHeight('160')
                                                                                ->required()
                                                                                ->panelLayout('integrated')
                                                                                ->columnSpanFull(),
                                                                        ])
                                                                        ->columnSpan(1),

                                                                    // Video Details
                                                                    Fieldset::make('Video Details')
                                                                        ->schema([
                                                                            TextInput::make('video.id')
                                                                                ->label('YouTube Video ID')
                                                                                ->required()
                                                                                ->placeholder('e.g., dQw4w9WgXcQ')
                                                                                ->suffixIcon('heroicon-o-video-camera')
                                                                                ->columnSpanFull(),

                                                                            TextInput::make('video.signature')
                                                                                ->label('Signature')
                                                                                ->required()
                                                                                ->placeholder('e.g., jNoRG1I7lZ_eJnLp')
                                                                                ->suffixIcon('heroicon-o-key')
                                                                                ->columnSpanFull(),
                                                                        ])
                                                                        ->columnSpan(1),
                                                                ]),
                                                        ])
                                                        ->columns(1)
                                                        ->defaultItems(3)
                                                        ->minItems(1)
                                                        ->maxItems(12)
                                                        ->collapsible()
                                                        ->itemLabel(fn(array $state): ?string => '🎥 ' . ($state['title'] ?? 'Video Item'))
                                                        ->addActionLabel('+ Add Video to Grid')
                                                        ->reorderable()
                                                        ->cloneable()
                                                        ->reorderableWithButtons(),
                                                ]),

                                            // ========================================
                                            // BLOCK: SINGLE FEATURED VIDEO
                                            // ========================================
                                            Block::make('video')
                                                ->label('Featured Video')
                                                ->icon('heroicon-o-video-camera')
                                                ->schema([
                                                    Grid::make(2)
                                                        ->schema([
                                                            // Video Thumbnail
                                                            Fieldset::make('Thumbnail')
                                                                ->schema([
                                                                    FileUpload::make('cover')
                                                                        ->label('Video Cover')
                                                                        ->image()
                                                                        ->disk('public')
                                                                        ->visibility('public')
                                                                        ->directory(fn($record) => $record?->dir() ?? 'videos/covers')
                                                                        ->imageEditor()
                                                                        ->imageEditorAspectRatios([
                                                                            '16:9' => '16:9 (Recommended)',
                                                                        ])
                                                                        ->imagePreviewHeight('240')
                                                                        ->required()
                                                                        ->panelLayout('integrated')
                                                                        ->columnSpanFull(),
                                                                ])
                                                                ->columnSpan(1),

                                                            // Video Details
                                                            Fieldset::make('Video Configuration')
                                                                ->schema([
                                                                    TextInput::make('video.id')
                                                                        ->label('YouTube Video ID')
                                                                        ->placeholder('dQw4w9WgXcQ')
                                                                        ->required()
                                                                        ->suffixIcon('heroicon-o-link')
                                                                        ->columnSpanFull(),

                                                                    TextInput::make('video.signature')
                                                                        ->label('Video Signature')
                                                                        ->placeholder('jNoRG1I7lZ_eJnLp')
                                                                        ->required()
                                                                        ->suffixIcon('heroicon-o-key')
                                                                        ->columnSpanFull(),
                                                                ])
                                                                ->columnSpan(1),
                                                        ]),
                                                ]),

                                            // ========================================
                                            // BLOCK: RICH TEXT CONTENT
                                            // ========================================
                                            Block::make('paragraphs')
                                                ->label('Rich Text Content')
                                                ->icon('heroicon-o-document-text')
                                                ->schema([
                                                    Repeater::make('content')
                                                        ->label('Text Sections')
                                                        ->schema([
                                                            RichEditor::make('value')
                                                                ->label('Content')
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
                                                                ->disableToolbarButtons([
                                                                    'strike',
                                                                ])
                                                                ->columnSpanFull(),
                                                        ])
                                                        ->addActionLabel('+ Add Text Section')
                                                        ->collapsible()
                                                        ->cloneable()
                                                        ->reorderable()
                                                        ->reorderableWithButtons()
                                                        ->itemLabel(fn(array $state): ?string => '📝 ' . str($state['value'] ?? 'Text Section')->stripTags()->limit(50))
                                                        ->columnSpanFull()
                                                        ->defaultItems(1),
                                                ]),

                                            // ========================================
                                            // BLOCK: SECTION HEADING
                                            // ========================================
                                            Block::make('heading')
                                                ->label('Section Heading')
                                                ->icon('heroicon-o-bars-3-bottom-left')
                                                ->schema([
                                                    TextInput::make('title')
                                                        ->label('Heading Text')
                                                        ->required()
                                                        ->placeholder('Enter your section heading')
                                                        ->suffixIcon('heroicon-o-h1')
                                                        ->columnSpanFull(),
                                                ]),

                                            // ========================================
                                            // BLOCK: IMAGE
                                            // ========================================
                                            Block::make('image')
                                                ->label('Image')
                                                ->icon('heroicon-o-photo')
                                                ->schema([
                                                    Grid::make(2)
                                                        ->schema([
                                                            // Image Upload
                                                            Fieldset::make('Image File')
                                                                ->schema([
                                                                    FileUpload::make('src')
                                                                        ->label('Image')
                                                                        ->disk('public')
                                                                        ->visibility('public')
                                                                        ->directory(fn($record) => $record?->dir() ?? 'posts/images')
                                                                        ->image()
                                                                        ->imageEditor()
                                                                        ->imageEditorAspectRatios([
                                                                            null => 'Free Form',
                                                                            '16:9' => '16:9 (Landscape)',
                                                                            '4:3' => '4:3 (Standard)',
                                                                            '1:1' => '1:1 (Square)',
                                                                            '9:16' => '9:16 (Portrait)',
                                                                        ])
                                                                        ->imagePreviewHeight('320')
                                                                        ->maxSize(5120)
                                                                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                                                        ->helperText('Supported formats: JPG, PNG, WebP • Maximum: 5MB')
                                                                        ->required()
                                                                        ->panelLayout('integrated')
                                                                        ->columnSpanFull(),
                                                                ])
                                                                ->columnSpan(1),

                                                            // Image Metadata
                                                            Fieldset::make('Image Metadata')
                                                                ->schema([
                                                                    TextInput::make('alt')
                                                                        ->label('Alternative Text (SEO)')
                                                                        ->placeholder('Describe the image for accessibility')
                                                                        ->helperText('Important for SEO and screen readers')
                                                                        ->suffixIcon('heroicon-o-eye')
                                                                        ->columnSpanFull(),

                                                                    TextInput::make('caption')
                                                                        ->label('Caption')
                                                                        ->placeholder('Add a caption below the image')
                                                                        ->helperText('Optional - displayed beneath the image')
                                                                        ->suffixIcon('heroicon-o-chat-bubble-bottom-center-text')
                                                                        ->columnSpanFull(),
                                                                ])
                                                                ->columnSpan(1),
                                                        ]),
                                                ]),

                                            // ========================================
                                            // BLOCK: BLOCKQUOTE
                                            // ========================================
                                            Block::make('blockquote')
                                                ->label('Quote Block')
                                                ->icon('heroicon-o-chat-bubble-left-ellipsis')
                                                ->schema([
                                                    Fieldset::make('Quote')
                                                        ->schema([
                                                            Textarea::make('quote')
                                                                ->label('Quote Text')
                                                                ->required()
                                                                ->rows(4)
                                                                ->placeholder('"Enter the inspirational or notable quote here..."')
                                                                ->helperText('The main quotation text')
                                                                ->columnSpanFull(),
                                                        ])
                                                        ->columnSpanFull(),

                                                    Grid::make(2)
                                                        ->schema([
                                                            Fieldset::make('Author Information')
                                                                ->schema([
                                                                    TextInput::make('author')
                                                                        ->label('Author Name')
                                                                        ->placeholder('e.g., Jane Smith')
                                                                        ->suffixIcon('heroicon-o-user')
                                                                        ->columnSpanFull(),

                                                                    TextInput::make('author_title')
                                                                        ->label('Author Title / Role')
                                                                        ->placeholder('e.g., CEO at Tech Company')
                                                                        ->suffixIcon('heroicon-o-briefcase')
                                                                        ->columnSpanFull(),
                                                                ])
                                                                ->columnSpan(2),
                                                        ]),
                                                ]),

                                            // ========================================
                                            // BLOCK: SIDE-BY-SIDE LAYOUT
                                            // ========================================
                                            Block::make('side-by-side')
                                                ->label('Side-by-Side Layout')
                                                ->icon('heroicon-o-rectangle-group')
                                                ->schema([
                                                    Grid::make(2)
                                                        ->schema([
                                                            // Featured Image
                                                            Fieldset::make('Featured Image')
                                                                ->schema([
                                                                    FileUpload::make('image')
                                                                        ->label('Image')
                                                                        ->disk('public')
                                                                        ->visibility('public')
                                                                        ->directory(fn($record) => $record?->dir() ?? 'post_modules')
                                                                        ->image()
                                                                        ->imageEditor()
                                                                        ->imageEditorAspectRatios([
                                                                            '1:1' => '1:1 (Square)',
                                                                            '4:3' => '4:3 (Standard)',
                                                                            '16:9' => '16:9 (Wide)',
                                                                        ])
                                                                        ->imagePreviewHeight('280')
                                                                        ->maxSize(5120)
                                                                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                                                        ->required()
                                                                        ->panelLayout('integrated')
                                                                        ->helperText('Display image for this section')
                                                                        ->columnSpanFull(),
                                                                ])
                                                                ->columnSpan(1),

                                                            // Content
                                                            Fieldset::make('Content')
                                                                ->schema([
                                                                    TextInput::make('heading')
                                                                        ->label('Section Heading')
                                                                        ->placeholder('Enter a compelling heading')
                                                                        ->suffixIcon('heroicon-o-h2')
                                                                        ->columnSpanFull(),

                                                                    Textarea::make('paragraph')
                                                                        ->label('Description')
                                                                        ->rows(4)
                                                                        ->placeholder('Provide detailed information about this section')
                                                                        ->helperText('Supporting content for this layout')
                                                                        ->columnSpanFull(),
                                                                ])
                                                                ->columnSpan(1),
                                                        ]),

                                                    Fieldset::make('Key Highlights')
                                                        ->schema([
                                                            Repeater::make('list')
                                                                ->label('Highlights')
                                                                ->schema([
                                                                    Grid::make(6)
                                                                        ->schema([
                                                                            TextInput::make('value')
                                                                                ->label('Point')
                                                                                ->placeholder('Enter a key point or feature')
                                                                                ->required()
                                                                                ->columnSpan(5),

                                                                            Select::make('icon')
                                                                                ->label('Icon')
                                                                                ->preload()
                                                                                ->searchable()
                                                                                ->native(false)
                                                                                ->options([
                                                                                    'check' => '✓ Check Mark',
                                                                                    'star' => '★ Star',
                                                                                    'arrow' => '→ Arrow',
                                                                                    'dot' => '• Bullet',
                                                                                ])
                                                                                ->default('check')
                                                                                ->columnSpan(1),
                                                                        ]),
                                                                ])
                                                                ->itemLabel(fn(array $state): ?string => '• ' . str($state['value'] ?? 'Highlight')->limit(60))
                                                                ->addActionLabel('+ Add Highlight')
                                                                ->collapsible()
                                                                ->reorderable()
                                                                ->reorderableWithButtons()
                                                                ->cloneable()
                                                                ->defaultItems(3)
                                                                ->columnSpanFull(),
                                                        ])
                                                        ->columnSpanFull(),
                                                ]),

                                            // ========================================
                                            // BLOCK: DIVIDER
                                            // ========================================
                                            Block::make('divider')
                                                ->label('Visual Separator')
                                                ->icon('heroicon-o-minus')
                                                ->schema([
                                                    Select::make('style')
                                                        ->label('Separator Style')
                                                        ->preload()
                                                        ->searchable()
                                                        ->native(false)
                                                        ->options([
                                                            'line' => '━ Solid Line',
                                                            'dashed' => '╍ Dashed Line',
                                                            'dotted' => '┅ Dotted Line',
                                                            'space' => '⎯ Empty Space',
                                                        ])
                                                        ->default('line')
                                                        ->helperText('Add visual separation between content sections')
                                                        ->columnSpanFull(),
                                                ]),
                                        ])
                                        ->blockNumbers(false)
                                        ->addActionLabel('+ Add Content Block')
                                        ->collapsible()
                                        ->cloneable()
                                        ->reorderable()
                                        ->reorderableWithButtons()
                                        ->blockPickerColumns(3)
                                        ->blockPickerWidth('3xl')
                                        ->columnSpanFull(),
                                ])
                                ->collapsible()
                                ->persistCollapsed(),
                        ]),
                ])
                ->columnSpanFull()
                ->persistTabInQueryString()
                ->activeTab(1)
                ->contained(false),
        ]);
    }
}
