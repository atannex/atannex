<?php

namespace App\Filament\Resources\PostModules\Schemas;

use App\Enums\HeadingLevel;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PostModuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Group::make()
                ->schema([
                    Section::make('Essential Configuration')
                        ->description('Link this module to a post')
                        ->icon('heroicon-o-link')
                        ->schema([
                            Grid::make(1)
                                ->schema([
                                    Select::make('post_id')
                                        ->label('Associated Post')
                                        ->relationship('post', 'title')
                                        ->searchable()
                                        ->preload()
                                        ->placeholder('Select a post...')
                                        ->helperText('Choose the post this module belongs to')
                                        ->required()
                                        ->native(false)
                                        ->prefixIcon('heroicon-o-document-text'),
                                ]),
                        ])
                        ->collapsible()
                        ->persistCollapsed(),

                    Section::make('Content Builder')
                        ->description('Create rich content with flexible building blocks')
                        ->icon('heroicon-o-squares-plus')
                        ->schema([
                            Grid::make(1)
                                ->schema([
                                    Builder::make('content')
                                        ->blocks([
                                            Block::make('paragraphs')
                                                ->label('Text Content')
                                                ->icon('heroicon-o-bars-3-bottom-left')
                                                ->schema([
                                                    Repeater::make('content')
                                                        ->label('Paragraphs')
                                                        ->schema([
                                                            RichEditor::make('value')
                                                                ->label('Content')
                                                                ->placeholder('Write your content here...')
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
                                                        ->addActionLabel('+ Add Paragraph')
                                                        ->columnSpanFull(),
                                                ]),

                                            Block::make('heading')
                                                ->label('Heading')
                                                ->icon('heroicon-o-bars-3')
                                                ->schema([
                                                    Grid::make(3)
                                                        ->schema([
                                                            Select::make('level')
                                                                ->label('Level')
                                                                ->options(HeadingLevel::asSelectArray())
                                                                ->default(HeadingLevel::H2)
                                                                ->required()
                                                                ->native(false)
                                                                ->columnSpan(1),

                                                            TextInput::make('content')
                                                                ->label('Heading Text')
                                                                ->placeholder('Enter heading...')
                                                                ->required()
                                                                ->columnSpan(2),
                                                        ]),
                                                ]),

                                            Block::make('blockquote')
                                                ->label('Quote')
                                                ->icon('heroicon-o-chat-bubble-left-right')
                                                ->schema([
                                                    Textarea::make('content')
                                                        ->label('Quote Text')
                                                        ->placeholder('Enter quote...')
                                                        ->required()
                                                        ->rows(3)
                                                        ->columnSpanFull(),

                                                    TextInput::make('attribution')
                                                        ->label('Attribution')
                                                        ->placeholder('— Author, Source')
                                                        ->helperText('Optional attribution'),
                                                ]),

                                            Block::make('image')
                                                ->label('Image')
                                                ->icon('heroicon-o-photo')
                                                ->schema([
                                                    FileUpload::make('src')
                                                        ->label('Image')
                                                        ->disk('public')
                                                        ->visibility('public')
                                                        ->directory('content/images')
                                                        ->image()
                                                        ->imageEditor()
                                                        ->imageEditorAspectRatioOptions([
                                                            null => 'Free Form',
                                                            '16:9' => '16:9',
                                                            '4:3' => '4:3',
                                                            '1:1' => 'Square',
                                                        ])
                                                        ->imagePreviewHeight('280')
                                                        ->maxSize(5120)
                                                        ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png', 'image/webp'])
                                                        ->helperText('Max 5MB • JPG, PNG, or WebP')
                                                        ->required()
                                                        ->deletable()
                                                        ->columnSpanFull(),

                                                    Grid::make(2)
                                                        ->schema([
                                                            TextInput::make('alt')
                                                                ->label('Alt Text')
                                                                ->placeholder('Describe the image')
                                                                ->helperText('For accessibility & SEO'),

                                                            Textarea::make('caption')
                                                                ->label('Caption')
                                                                ->placeholder('Optional caption')
                                                                ->rows(2),
                                                        ]),
                                                ]),

                                            Block::make('side-by-side')
                                                ->label('Side-by-Side')
                                                ->icon('heroicon-o-rectangle-group')
                                                ->schema([
                                                    Grid::make(['default' => 1, 'lg' => 2])
                                                        ->schema([
                                                            FileUpload::make('image')
                                                                ->label('Image')
                                                                ->disk('public')
                                                                ->visibility('public')
                                                                ->directory('content/layouts')
                                                                ->image()
                                                                ->imageEditor()
                                                                ->imageEditorAspectRatioOptions([
                                                                    '1:1' => 'Square',
                                                                    '4:3' => 'Standard',
                                                                    '16:9' => 'Wide',
                                                                ])
                                                                ->imagePreviewHeight('240')
                                                                ->maxSize(5120)
                                                                ->required()
                                                                ->deletable()
                                                                ->columnSpan(1),

                                                            Grid::make(1)
                                                                ->schema([
                                                                    TextInput::make('heading')
                                                                        ->label('Heading')
                                                                        ->placeholder('Section title')
                                                                        ->required(),

                                                                    RichEditor::make('content')
                                                                        ->label('Content')
                                                                        ->placeholder('Section content...')
                                                                        ->required()
                                                                        ->disableToolbarButtons(['codeBlock'])
                                                                        ->toolbarButtons([
                                                                            'bold',
                                                                            'italic',
                                                                            'link',
                                                                            'bulletList',
                                                                        ]),
                                                                ])
                                                                ->columnSpan(1),
                                                        ]),

                                                    Repeater::make('highlights')
                                                        ->label('Highlights')
                                                        ->schema([
                                                            TextInput::make('text')
                                                                ->label('Highlight')
                                                                ->required()
                                                                ->placeholder('Key point')
                                                                ->columnSpanFull(),
                                                        ])
                                                        ->addActionLabel('+ Add Highlight')
                                                        ->collapsible()
                                                        ->itemLabel(fn (array $state): ?string => '✓ '.($state['text'] ?? 'New'))
                                                        ->defaultItems(0)
                                                        ->columnSpanFull(),
                                                ]),
                                        ])
                                        ->blockNumbers(false)
                                        ->addActionLabel('+ Add Block')
                                        ->collapsible()
                                        ->cloneable()
                                        ->reorderableWithButtons()
                                        ->blockPickerColumns(['default' => 2, 'lg' => 2])
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
