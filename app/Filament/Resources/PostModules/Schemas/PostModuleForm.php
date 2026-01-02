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
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Group;

class PostModuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            // ============================================
            // MODULE CONFIGURATION
            // ============================================
            Group::make()
                ->schema([
                    Section::make('Module Configuration')
                        ->description('Essential settings for this post module')
                        ->icon('heroicon-o-cog-6-tooth')
                        ->schema([
                            Grid::make(2)
                                ->schema([
                                    Select::make('post_id')
                                        ->label('Associated Post')
                                        ->relationship('post', 'title')
                                        ->searchable()
                                        ->preload()
                                        ->placeholder('Search and select a post')
                                        ->helperText('Connect this module to an existing post')
                                        ->required()
                                        ->native(false)
                                        ->prefixIcon('heroicon-o-link')
                                        ->columnSpan(1),

                                    Select::make('type')
                                        ->label('Content Type')
                                        ->options(PostType::asSelectArray())
                                        ->default(PostType::ARTICLE)
                                        ->preload()
                                        ->searchable()
                                        ->required()
                                        ->native(false)
                                        ->prefixIcon('heroicon-o-tag')
                                        ->helperText('Defines how content is displayed and categorized')
                                        ->columnSpan(1),
                                ]),
                        ])
                        ->collapsible()
                        ->persistCollapsed(),
                ])
                ->columnSpanFull(),

            // ============================================
            // VIDEO CONTENT SECTION
            // ============================================
            Group::make()
                ->schema([
                    Section::make('Video Content')
                        ->description('Add video content via upload or YouTube embed')
                        ->icon('heroicon-o-play-circle')
                        ->schema([
                            Grid::make(['default' => 1, 'lg' => 2])
                                ->schema([
                                    // Left Column: Uploaded Video
                                    Group::make()
                                        ->schema([
                                            Section::make('Direct Video Upload')
                                                ->schema([
                                                    Grid::make(1)
                                                        ->schema([
                                                            FileUpload::make('video.path')
                                                                ->label('Video File')
                                                                ->disk('public')
                                                                ->visibility('public')
                                                                ->directory(fn($record) => $record?->dir() ?? 'post_modules/videos')
                                                                ->acceptedFileTypes([
                                                                    'video/mp4',
                                                                    'video/webm',
                                                                    'video/ogg',
                                                                ])
                                                                ->maxSize(51200)
                                                                ->helperText('Upload MP4, WebM, or OGG • Max: 50MB')
                                                                ->imagePreviewHeight('220')
                                                                ->panelLayout('integrated')
                                                                ->panelAspectRatio('16:9')
                                                                ->columnSpanFull(),

                                                            FileUpload::make('video.poster')
                                                                ->label('Video Poster (Thumbnail)')
                                                                ->disk('public')
                                                                ->directory(fn($record) => $record?->dir() ?? 'post_modules/posters')
                                                                ->visibility('public')
                                                                ->image()
                                                                ->imageEditor()
                                                                ->imageEditorAspectRatios([
                                                                    '16:9' => '16:9 (Recommended)',
                                                                    '4:3' => '4:3 (Standard)',
                                                                    '1:1' => '1:1 (Square)',
                                                                ])
                                                                ->imagePreviewHeight('180')
                                                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                                                ->maxSize(2048)
                                                                ->helperText('Thumbnail shown before playback • Max: 2MB')
                                                                ->panelLayout('integrated')
                                                                ->columnSpanFull(),
                                                        ]),
                                                ])
                                                ->compact(),
                                        ])
                                        ->columnSpan(['default' => 1, 'lg' => 1]),

                                    // Right Column: YouTube Video
                                    Group::make()
                                        ->schema([
                                            Section::make('YouTube Embed')
                                                ->schema([
                                                    Grid::make(1)
                                                        ->schema([
                                                            TextInput::make('video.id')
                                                                ->label('YouTube Video ID')
                                                                ->placeholder('dQw4w9WgXcQ')
                                                                ->default('')
                                                                ->dehydrated()
                                                                ->prefix('youtube.com/watch?v=')
                                                                ->suffixIcon('heroicon-o-video-camera')
                                                                ->helperText('Extract ID from YouTube URL')
                                                                ->columnSpanFull(),

                                                            TextInput::make('video.signature')
                                                                ->label('YouTube Signature')
                                                                ->placeholder('jNoRG1I7lZ_eJnLp')
                                                                ->default('')
                                                                ->dehydrated()
                                                                ->suffixIcon('heroicon-o-key')
                                                                ->helperText('Required for enhanced embedded features')
                                                                ->columnSpanFull(),

                                                            FileUpload::make('video.thumbnail')
                                                                ->label('Custom Thumbnail (Optional)')
                                                                ->image()
                                                                ->disk('public')
                                                                ->visibility('public')
                                                                ->directory(fn($record) => $record?->dir() ?? 'post_modules/thumbnails')
                                                                ->imageEditor()
                                                                ->imageEditorAspectRatios([
                                                                    '16:9' => '16:9 (YouTube Standard)',
                                                                ])
                                                                ->imagePreviewHeight('180')
                                                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                                                ->maxSize(2048)
                                                                ->helperText('Override YouTube default • Max: 2MB')
                                                                ->panelLayout('integrated')
                                                                ->columnSpanFull(),
                                                        ]),
                                                ])
                                                ->compact(),
                                        ])
                                        ->columnSpan(['default' => 1, 'lg' => 1]),
                                ]),
                        ])
                        ->collapsible()
                        ->collapsed()
                        ->persistCollapsed(),
                ])
                ->columnSpanFull(),

            // ============================================
            // CONTENT BUILDER
            // ============================================
            Group::make()
                ->schema([
                    Section::make('Content Builder')
                        ->description('Compose your post using flexible, drag-and-drop content blocks')
                        ->icon('heroicon-o-squares-plus')
                        ->schema([
                            Grid::make(1)
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
                                                                    Group::make()
                                                                        ->schema([
                                                                            Section::make('Cover Image')
                                                                                ->schema([
                                                                                    Grid::make(1)
                                                                                        ->schema([
                                                                                            FileUpload::make('cover')
                                                                                                ->label('Thumbnail')
                                                                                                ->image()
                                                                                                ->disk('public')
                                                                                                ->visibility('public')
                                                                                                ->directory(fn($record) => $record?->dir() ?? 'content/videos')
                                                                                                ->imageEditor()
                                                                                                ->imageEditorAspectRatios([
                                                                                                    '16:9' => '16:9 (YouTube Standard)',
                                                                                                ])
                                                                                                ->imagePreviewHeight('160')
                                                                                                ->required()
                                                                                                ->panelLayout('integrated')
                                                                                                ->columnSpanFull(),
                                                                                        ]),
                                                                                ])
                                                                                ->compact()
                                                                                ->hiddenLabel(),
                                                                        ])
                                                                        ->columnSpan(1),

                                                                    Group::make()
                                                                        ->schema([
                                                                            Section::make('Video Information')
                                                                                ->schema([
                                                                                    Grid::make(1)
                                                                                        ->schema([
                                                                                            TextInput::make('video.id')
                                                                                                ->label('YouTube Video ID')
                                                                                                ->required()
                                                                                                ->placeholder('dQw4w9WgXcQ')
                                                                                                ->suffixIcon('heroicon-o-video-camera')
                                                                                                ->columnSpanFull(),

                                                                                            TextInput::make('video.signature')
                                                                                                ->label('Signature')
                                                                                                ->required()
                                                                                                ->placeholder('jNoRG1I7lZ_eJnLp')
                                                                                                ->suffixIcon('heroicon-o-key')
                                                                                                ->columnSpanFull(),
                                                                                        ]),
                                                                                ])
                                                                                ->compact()
                                                                                ->hiddenLabel(),
                                                                        ])
                                                                        ->columnSpan(1),
                                                                ]),
                                                        ])
                                                        ->columns(1)
                                                        ->defaultItems(3)
                                                        ->minItems(1)
                                                        ->maxItems(12)
                                                        ->collapsible()
                                                        ->itemLabel(fn(array $state): ?string => '🎥 Video ' . ($state['video']['id'] ?? 'Item'))
                                                        ->addActionLabel('+ Add Video')
                                                        ->reorderable()
                                                        ->cloneable()
                                                        ->reorderableWithButtons(),
                                                ]),

                                            // ========================================
                                            // BLOCK: FEATURED VIDEO
                                            // ========================================
                                            Block::make('video')
                                                ->label('Featured Video')
                                                ->icon('heroicon-o-video-camera')
                                                ->schema([
                                                    Grid::make(2)
                                                        ->schema([
                                                            Group::make()
                                                                ->schema([
                                                                    Section::make('Video Cover')
                                                                        ->schema([
                                                                            Grid::make(1)
                                                                                ->schema([
                                                                                    FileUpload::make('cover')
                                                                                        ->label('Thumbnail')
                                                                                        ->image()
                                                                                        ->disk('public')
                                                                                        ->visibility('public')
                                                                                        ->directory(fn($record) => $record?->dir() ?? 'content/featured-videos')
                                                                                        ->imageEditor()
                                                                                        ->imageEditorAspectRatios([
                                                                                            '16:9' => '16:9 (Recommended)',
                                                                                        ])
                                                                                        ->imagePreviewHeight('240')
                                                                                        ->required()
                                                                                        ->panelLayout('integrated')
                                                                                        ->columnSpanFull(),
                                                                                ]),
                                                                        ])
                                                                        ->compact()
                                                                        ->hiddenLabel(),
                                                                ])
                                                                ->columnSpan(1),

                                                            Group::make()
                                                                ->schema([
                                                                    Section::make('Video Details')
                                                                        ->schema([
                                                                            Grid::make(1)
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
                                                                                ]),
                                                                        ])
                                                                        ->compact()
                                                                        ->hiddenLabel(),
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
                                                            Group::make()
                                                                ->schema([
                                                                    Section::make('Image Upload')
                                                                        ->schema([
                                                                            Grid::make(1)
                                                                                ->schema([
                                                                                    FileUpload::make('src')
                                                                                        ->label('Image')
                                                                                        ->disk('public')
                                                                                        ->visibility('public')
                                                                                        ->directory(fn($record) => $record?->dir() ?? 'content/images')
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
                                                                                        ->helperText('JPG, PNG, WebP • Max: 5MB')
                                                                                        ->required()
                                                                                        ->panelLayout('integrated')
                                                                                        ->columnSpanFull(),
                                                                                ]),
                                                                        ])
                                                                        ->compact()
                                                                        ->hiddenLabel(),
                                                                ])
                                                                ->columnSpan(1),

                                                            Group::make()
                                                                ->schema([
                                                                    Section::make('Image Information')
                                                                        ->schema([
                                                                            Grid::make(1)
                                                                                ->schema([
                                                                                    TextInput::make('alt')
                                                                                        ->label('Alt Text (SEO)')
                                                                                        ->placeholder('Describe the image')
                                                                                        ->helperText('Important for accessibility and SEO')
                                                                                        ->suffixIcon('heroicon-o-eye')
                                                                                        ->columnSpanFull(),

                                                                                    TextInput::make('caption')
                                                                                        ->label('Caption')
                                                                                        ->placeholder('Add a caption')
                                                                                        ->helperText('Displayed beneath the image')
                                                                                        ->suffixIcon('heroicon-o-chat-bubble-bottom-center-text')
                                                                                        ->columnSpanFull(),
                                                                                ]),
                                                                        ])
                                                                        ->compact()
                                                                        ->hiddenLabel(),
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
                                                    Grid::make(1)
                                                        ->schema([
                                                            Textarea::make('quote')
                                                                ->label('Quote Text')
                                                                ->required()
                                                                ->rows(4)
                                                                ->placeholder('"Enter the inspirational or notable quote here..."')
                                                                ->helperText('The main quotation text')
                                                                ->columnSpanFull(),
                                                        ]),

                                                    Grid::make(2)
                                                        ->schema([
                                                            TextInput::make('author')
                                                                ->label('Author Name')
                                                                ->placeholder('e.g., Jane Smith')
                                                                ->suffixIcon('heroicon-o-user')
                                                                ->columnSpan(1),

                                                            TextInput::make('author_title')
                                                                ->label('Author Title / Role')
                                                                ->placeholder('e.g., CEO at Tech Company')
                                                                ->suffixIcon('heroicon-o-briefcase')
                                                                ->columnSpan(1),
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
                                                            Group::make()
                                                                ->schema([
                                                                    Section::make('Visual Element')
                                                                        ->schema([
                                                                            Grid::make(1)
                                                                                ->schema([
                                                                                    FileUpload::make('image')
                                                                                        ->label('Image')
                                                                                        ->disk('public')
                                                                                        ->visibility('public')
                                                                                        ->directory(fn($record) => $record?->dir() ?? 'content/layouts')
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
                                                                                ]),
                                                                        ])
                                                                        ->compact()
                                                                        ->hiddenLabel(),
                                                                ])
                                                                ->columnSpan(1),

                                                            Group::make()
                                                                ->schema([
                                                                    Section::make('Text Content')
                                                                        ->schema([
                                                                            Grid::make(1)
                                                                                ->schema([
                                                                                    TextInput::make('heading')
                                                                                        ->label('Section Heading')
                                                                                        ->placeholder('Enter a compelling heading')
                                                                                        ->suffixIcon('heroicon-o-h2')
                                                                                        ->columnSpanFull(),

                                                                                    Textarea::make('paragraph')
                                                                                        ->label('Description')
                                                                                        ->rows(6)
                                                                                        ->placeholder('Provide detailed information')
                                                                                        ->helperText('Supporting content for this section')
                                                                                        ->columnSpanFull(),
                                                                                ]),
                                                                        ])
                                                                        ->compact()
                                                                        ->hiddenLabel(),
                                                                ])
                                                                ->columnSpan(1),
                                                        ]),

                                                    Grid::make(1)
                                                        ->schema([
                                                            Group::make()
                                                                ->schema([
                                                                    Section::make('Key Highlights')
                                                                        ->schema([
                                                                            Grid::make(1)
                                                                                ->schema([
                                                                                    Repeater::make('list')
                                                                                        ->label('Bullet Points')
                                                                                        ->schema([
                                                                                            Grid::make(6)
                                                                                                ->schema([
                                                                                                    TextInput::make('value')
                                                                                                        ->label('Point')
                                                                                                        ->placeholder('Enter a key point')
                                                                                                        ->required()
                                                                                                        ->columnSpan(5),

                                                                                                    Select::make('icon')
                                                                                                        ->label('Icon')
                                                                                                        ->preload()
                                                                                                        ->searchable()
                                                                                                        ->native(false)
                                                                                                        ->options([
                                                                                                            'check' => '✓ Check',
                                                                                                            'star' => '★ Star',
                                                                                                            'arrow' => '→ Arrow',
                                                                                                            'dot' => '• Bullet',
                                                                                                        ])
                                                                                                        ->default('check')
                                                                                                        ->columnSpan(1),
                                                                                                ]),
                                                                                        ])
                                                                                        ->itemLabel(fn(array $state): ?string => '• ' . str($state['value'] ?? 'Highlight')->limit(60))
                                                                                        ->addActionLabel('+ Add Point')
                                                                                        ->collapsible()
                                                                                        ->reorderable()
                                                                                        ->reorderableWithButtons()
                                                                                        ->cloneable()
                                                                                        ->defaultItems(3)
                                                                                        ->columnSpanFull(),
                                                                                ]),
                                                                        ])
                                                                        ->compact()
                                                                        ->hiddenLabel(),
                                                                ])
                                                                ->columnSpanFull(),
                                                        ]),
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
                                                        ->helperText('Visual separation between sections')
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
                                ]),
                        ])
                        ->collapsible()
                        ->persistCollapsed(),
                ])
                ->columnSpanFull(),
        ]);
    }
}
