<?php

namespace App\Filament\Resources\PostModules\Schemas;

use App\Enums\HeadingLevel;
use Filament\Forms\Components\Radio;
use App\Enums\PostType;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Builder\Block;

class PostModuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            // ============================================================================
            // SECTION 1: Essential Configuration
            // ============================================================================
            Section::make('Essential Configuration')
                ->description('Link this module to a post and define its content type')
                ->icon('heroicon-o-cog-6-tooth')
                ->schema([
                    Grid::make(['default' => 1, 'md' => 2])
                        ->schema([
                            Select::make('post_id')
                                ->label('Associated Post')
                                ->relationship('post', 'title')
                                ->searchable()
                                ->preload()
                                ->placeholder('Select a post to associate with this module')
                                ->helperText('This module will be attached to the selected post')
                                ->required()
                                ->native(false)
                                ->prefixIcon('heroicon-o-document-text')
                                ->columnSpan(['default' => 'full', 'md' => 1]),

                            Select::make('type')
                                ->label('Module Type')
                                ->options(PostType::asSelectArray())
                                ->default(PostType::ARTICLE)
                                ->searchable()
                                ->required()
                                ->native(false)
                                ->prefixIcon('heroicon-o-tag')
                                ->helperText('Determines how this content is displayed and categorized')
                                ->live()
                                ->columnSpan(['default' => 'full', 'md' => 1]),
                        ]),
                ])
                ->columnSpanFull(),

            // ============================================================================
            // SECTION 2: Content Builder (Primary Content)
            // ============================================================================
            Section::make('Content Builder')
                ->description('Build your post content using flexible, drag-and-drop blocks')
                ->icon('heroicon-o-squares-plus')
                ->schema([
                    Builder::make('content')
                        ->columnSpanFull()
                        ->blocks([

                            Block::make('paragraphs')
                                ->label('Paragraphs')
                                ->icon('heroicon-o-bars-3-bottom-left')
                                ->schema([
                                    Repeater::make('content')
                                        ->label('Paragraph Content')
                                        ->schema([
                                            RichEditor::make('value')
                                                ->label('Text')
                                                ->placeholder('Write your paragraph content here...')
                                                ->required()
                                                ->disableToolbarButtons(['codeBlock'])
                                                ->toolbarButtons([
                                                    'bold',
                                                    'italic',
                                                    'link',
                                                    'bulletList',
                                                    'orderedList',
                                                ]),
                                        ])
                                        ->minItems(1)
                                        ->columnSpanFull(),
                                ]),

                            Block::make('heading')
                                ->label('Heading')
                                ->icon('heroicon-o-bars-3')
                                ->schema([
                                    Grid::make(3)
                                        ->schema([
                                            Select::make('level')
                                                ->label('Heading Level')
                                                ->options(HeadingLevel::asSelectArray())
                                                ->default(HeadingLevel::H2)
                                                ->required()
                                                ->searchable()
                                                ->preload()
                                                ->native(false)
                                                ->columnSpan(1),

                                            TextInput::make('content')
                                                ->label('Heading Text')
                                                ->placeholder('Enter your heading')
                                                ->required()
                                                ->columnSpan(2),
                                        ]),
                                ]),

                            Block::make('blockquote')
                                ->label('Blockquote')
                                ->icon('heroicon-o-chat-bubble-left-right')
                                ->schema([
                                    Textarea::make('content')
                                        ->label('Quote')
                                        ->placeholder('Enter the quote text...')
                                        ->required()
                                        ->rows(3)
                                        ->columnSpanFull(),

                                    TextInput::make('attribution')
                                        ->label('Attribution')
                                        ->placeholder('— Author Name, Source')
                                        ->helperText('Optional: Author or source of the quote'),
                                ]),

                            Block::make('image')
                                ->label('Image')
                                ->icon('heroicon-o-photo')
                                ->schema([
                                    FileUpload::make('src')
                                        ->label('Image File')
                                        ->disk('public')
                                        ->visibility('public')
                                        ->directory('content/images')
                                        ->image()
                                        ->imageEditor()
                                        ->imageEditorAspectRatioOptions([
                                            null => 'Free Form',
                                            '16:9' => '16:9 (Landscape)',
                                            '4:3' => '4:3 (Standard)',
                                            '1:1' => '1:1 (Square)',
                                            '9:16' => '9:16 (Portrait)',
                                        ])
                                        ->imagePreviewHeight('320')
                                        ->maxSize(5120)
                                        ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png', 'image/webp'])
                                        ->helperText('JPG, PNG, or WebP • Max 5MB • Previous images are automatically deleted on update')
                                        ->required()
                                        ->deletable(true)
                                        ->deleteUploadedFileUsing(fn($file) => true)
                                        ->columnSpanFull(),

                                    Grid::make(2)
                                        ->schema([
                                            TextInput::make('alt')
                                                ->label('Alt Text')
                                                ->placeholder('Describe the image for accessibility')
                                                ->helperText('Important for SEO and screen readers')
                                                ->columnSpan(1),

                                            Textarea::make('caption')
                                                ->label('Caption')
                                                ->placeholder('Optional caption or description')
                                                ->rows(2)
                                                ->columnSpan(1),
                                        ]),
                                ]),

                            Block::make('video')
                                ->label('Featured Video')
                                ->icon('heroicon-o-video-camera')
                                ->schema([
                                    Grid::make(['default' => 1, 'lg' => 2])
                                        ->schema([
                                            FileUpload::make('cover')
                                                ->label('Video Thumbnail')
                                                ->image()
                                                ->disk('public')
                                                ->visibility('public')
                                                ->directory('content/featured')
                                                ->imageEditor()
                                                ->imageEditorAspectRatioOptions([
                                                    '16:9' => '16:9 (Recommended)',
                                                ])
                                                ->imagePreviewHeight('240')
                                                ->required()
                                                ->deletable(true)
                                                ->deleteUploadedFileUsing(fn($file) => true)
                                                ->helperText('Upload a thumbnail for this video')
                                                ->columnSpan(['default' => 'full', 'lg' => 1]),

                                            Grid::make(1)
                                                ->schema([
                                                    TextInput::make('video.id')
                                                        ->label('YouTube Video ID')
                                                        ->placeholder('dQw4w9WgXcQ')
                                                        ->required()
                                                        ->suffixIcon('heroicon-o-link')
                                                        ->helperText('The ID from the YouTube URL'),

                                                    TextInput::make('video.signature')
                                                        ->label('Video Signature')
                                                        ->placeholder('jNoRG1I7lZ_eJnLp')
                                                        ->required()
                                                        ->suffixIcon('heroicon-o-key')
                                                        ->helperText('Signature for enhanced features'),
                                                ])
                                                ->columnSpan(['default' => 'full', 'lg' => 1]),
                                        ]),
                                ]),

                            Block::make('video_grid')
                                ->label('Video Grid')
                                ->icon('heroicon-o-film')
                                ->schema([
                                    Repeater::make('items')
                                        ->label('Video Collection')
                                        ->schema([
                                            Grid::make(['default' => 1, 'md' => 2])
                                                ->schema([
                                                    FileUpload::make('cover')
                                                        ->label('Thumbnail')
                                                        ->image()
                                                        ->disk('public')
                                                        ->visibility('public')
                                                        ->directory('content/videos')
                                                        ->imageEditor()
                                                        ->imageEditorAspectRatioOptions([
                                                            '16:9' => '16:9 (YouTube Standard)',
                                                        ])
                                                        ->imagePreviewHeight('160')
                                                        ->required()
                                                        ->deletable(true)
                                                        ->deleteUploadedFileUsing(fn($file) => true)
                                                        ->columnSpan(['default' => 'full', 'md' => 1]),

                                                    Grid::make(1)
                                                        ->schema([
                                                            TextInput::make('video.id')
                                                                ->label('YouTube Video ID')
                                                                ->required()
                                                                ->placeholder('dQw4w9WgXcQ')
                                                                ->suffixIcon('heroicon-o-video-camera'),

                                                            TextInput::make('video.signature')
                                                                ->label('Signature')
                                                                ->required()
                                                                ->placeholder('jNoRG1I7lZ_eJnLp')
                                                                ->suffixIcon('heroicon-o-key'),
                                                        ])
                                                        ->columnSpan(['default' => 'full', 'md' => 1]),
                                                ]),
                                        ])
                                        ->defaultItems(3)
                                        ->minItems(1)
                                        ->maxItems(12)
                                        ->collapsible()
                                        ->itemLabel(fn(array $state): ?string => '🎥 ' . ($state['video']['id'] ?? 'New Video'))
                                        ->addActionLabel('+ Add Video')
                                        ->reorderable()
                                        ->cloneable()
                                        ->reorderableWithButtons()
                                        ->columnSpanFull(),
                                ]),

                            Block::make('side-by-side')
                                ->label('Side-by-Side Layout')
                                ->icon('heroicon-o-rectangle-group')
                                ->schema([
                                    Grid::make(['default' => 1, 'lg' => 2])
                                        ->schema([
                                            FileUpload::make('image')
                                                ->label('Section Image')
                                                ->disk('public')
                                                ->visibility('public')
                                                ->directory('content/layouts')
                                                ->image()
                                                ->imageEditor()
                                                ->imageEditorAspectRatioOptions([
                                                    '1:1' => '1:1 (Square)',
                                                    '4:3' => '4:3 (Standard)',
                                                    '16:9' => '16:9 (Wide)',
                                                ])
                                                ->imagePreviewHeight('280')
                                                ->maxSize(5120)
                                                ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png', 'image/webp'])
                                                ->required()
                                                ->helperText('Image for this section (JPG, PNG, WebP • Max 5MB)')
                                                ->deletable(true)
                                                ->deleteUploadedFileUsing(fn($file) => true)
                                                ->columnSpan(['default' => 'full', 'lg' => 1]),

                                            Grid::make(1)
                                                ->schema([
                                                    TextInput::make('heading')
                                                        ->label('Section Heading')
                                                        ->placeholder('Enter the section title')
                                                        ->required(),

                                                    RichEditor::make('content')
                                                        ->label('Section Content')
                                                        ->placeholder('Write your content here...')
                                                        ->required()
                                                        ->disableToolbarButtons(['codeBlock'])
                                                        ->toolbarButtons([
                                                            'bold',
                                                            'italic',
                                                            'link',
                                                            'bulletList',
                                                        ]),
                                                ])
                                                ->columnSpan(['default' => 'full', 'lg' => 1]),
                                        ]),

                                    Repeater::make('highlights')
                                        ->label('Key Highlights')
                                        ->schema([
                                            TextInput::make('text')
                                                ->label('Highlight')
                                                ->required()
                                                ->placeholder('Enter a key point or feature')
                                                ->columnSpanFull(),
                                        ])
                                        ->addActionLabel('+ Add Highlight')
                                        ->collapsible()
                                        ->itemLabel(fn(array $state): ?string => '✓ ' . ($state['text'] ?? 'New Highlight'))
                                        ->defaultItems(0)
                                        ->reorderable()
                                        ->columnSpanFull(),
                                ]),
                        ])
                        ->blockNumbers(false)
                        ->addActionLabel('+ Add Content Block')
                        ->collapsible()
                        ->cloneable()
                        ->reorderable()
                        ->reorderableWithButtons()
                        ->blockPickerColumns(['default' => 2, 'lg' => 3])
                        ->blockPickerWidth('4xl')
                        ->columnSpanFull(),
                ])
                ->collapsible()
                ->persistCollapsed()
                ->columnSpanFull(),

            // ============================================================================
            // SECTION 3: Primary Media (Video-Specific Configuration)
            // ============================================================================
            Section::make('Primary Media')
                ->description('Configure the main video content for this module')
                ->icon('heroicon-o-film')
                ->schema([
                    Radio::make('video.source')
                        ->label('Media Source')
                        ->options([
                            'youtube' => 'YouTube Embed',
                            'upload' => 'Direct Upload',
                        ])
                        ->default('youtube')
                        ->inline()
                        ->live(),

                    Grid::make(['default' => 1, 'lg' => 2])
                        ->schema([
                            TextInput::make('video.id')
                                ->label('YouTube Video ID')
                                ->placeholder('dQw4w9WgXcQ')
                                ->prefix('youtube.com/watch?v=')
                                ->suffixIcon('heroicon-o-video-camera')
                                ->helperText('Enter the video ID from the YouTube URL (the part after "v=")')
                                ->required(fn($get) => $get('video.source') === 'youtube')
                                ->columnSpan(['default' => 'full', 'lg' => 1]),

                            TextInput::make('video.signature')
                                ->label('Video Signature')
                                ->placeholder('jNoRG1I7lZ_eJnLp')
                                ->suffixIcon('heroicon-o-key')
                                ->helperText('Optional signature for enhanced embed features')
                                ->columnSpan(['default' => 'full', 'lg' => 1]),
                        ])
                        ->visible(fn($get) => $get('video.source') === 'youtube'),

                    FileUpload::make('video.thumbnail')
                        ->label('Custom Video Thumbnail')
                        ->image()
                        ->disk('public')
                        ->directory('content/thumbnails')
                        ->imageEditor()
                        ->imageEditorAspectRatioOptions([
                            '16:9' => '16:9 (YouTube Standard)',
                        ])
                        ->imagePreviewHeight('200')
                        ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png', 'image/webp'])
                        ->maxSize(2048)
                        ->helperText('Upload a custom thumbnail to override YouTube\'s default (Max 2MB)')
                        ->deletable()
                        ->deleteUploadedFileUsing(fn($file) => true)
                        ->columnSpanFull()
                        ->visible(fn($get) => $get('video.source') === 'youtube'),

                    Grid::make(['default' => 1, 'lg' => 2])
                        ->schema([
                            FileUpload::make('video.path')
                                ->label('Video File')
                                ->disk('public')
                                ->directory('content/videos')
                                ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/ogg'])
                                ->maxSize(51200)
                                ->helperText('Upload your video file (Max 50MB)')
                                ->imagePreviewHeight('240')
                                ->panelLayout('integrated')
                                ->panelAspectRatio('16:9')
                                ->required(fn($get) => $get('video.source') === 'upload')
                                ->deletable()
                                ->deleteUploadedFileUsing(fn($file) => true)
                                ->columnSpan(['default' => 'full', 'lg' => 1]),

                            FileUpload::make('video.poster')
                                ->label('Video Poster Image')
                                ->disk('public')
                                ->directory('content/posters')
                                ->image()
                                ->imageEditor()
                                ->imageEditorAspectRatioOptions([
                                    '16:9' => '16:9 (Recommended)',
                                    '4:3' => '4:3 (Standard)',
                                    '1:1' => '1:1 (Square)',
                                ])
                                ->imagePreviewHeight('200')
                                ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png', 'image/webp'])
                                ->maxSize(2048)
                                ->helperText('Thumbnail image shown before video playback (Max 2MB)')
                                ->deletable()
                                ->deleteUploadedFileUsing(fn($file) => true)
                                ->columnSpan(['default' => 'full', 'lg' => 1]),
                        ])
                        ->visible(fn($get) => $get('video.source') === 'upload'),
                ])
                ->collapsible()
                ->collapsed()
                ->persistCollapsed()
                ->columnSpanFull()
                ->visible(fn($get) => $get('type') == PostType::VIDEO),
        ]);
    }
}
