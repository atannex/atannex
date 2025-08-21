<?php

namespace App\Filament\Resources\PostModules\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Builder;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;

class PostModuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Group::make()
                ->schema([
                    Section::make('Post Configuration')
                        ->description('Configure the basic settings for this post module')
                        ->icon('heroicon-o-cog-6-tooth')
                        ->schema([
                            Grid::make(2)
                                ->schema([
                                    Select::make('post_id')
                                        ->relationship('post', 'title')
                                        ->searchable()
                                        ->preload()
                                        ->placeholder('Select a post...')
                                        ->default(null)
                                        ->columnSpan(2),
                                ])
                        ])
                        ->collapsible()
                        ->collapsed(false),

                    Section::make('Module Content')
                        ->description('Build your post content using these flexible content blocks')
                        ->icon('heroicon-o-building-library')
                        ->schema([
                            Grid::make(1)
                                ->schema([
                                    Builder::make('module_content')
                                        ->label('')
                                        ->columnSpanFull()
                                        ->blocks([

                                            Builder\Block::make('paragraphs')
                                                ->label('Paragraphs')
                                                ->icon('heroicon-o-document-text')
                                                ->schema([
                                                    Group::make()
                                                        ->schema([
                                                            Section::make('Text Content')
                                                                ->schema([
                                                                    Grid::make(1)
                                                                        ->schema([
                                                                            Repeater::make('content')
                                                                                ->label('Paragraphs')
                                                                                ->schema([
                                                                                    Textarea::make('value')
                                                                                        ->label('Text Content')
                                                                                        ->rows(4)
                                                                                        ->placeholder('Enter your paragraph text...')
                                                                                        ->columnSpanFull(),
                                                                                ])
                                                                                ->itemLabel(fn(array $state): ?string => str($state['value'] ?? '')->limit(50) . '...')
                                                                                ->addActionLabel('Add Paragraph')
                                                                                ->collapsible()
                                                                                ->cloneable()
                                                                                ->columnSpanFull(),
                                                                        ])
                                                                ])
                                                                ->compact(),
                                                        ])
                                                ]),

                                            Builder\Block::make('heading')
                                                ->label('Heading')
                                                ->icon('heroicon-o-h1')
                                                ->schema([
                                                    Group::make()
                                                        ->schema([
                                                            Section::make('Heading Configuration')
                                                                ->schema([
                                                                    Grid::make(1)
                                                                        ->schema([
                                                                            TextInput::make('title')
                                                                                ->label('Heading Text')
                                                                                ->required()
                                                                                ->placeholder('Enter heading text...')
                                                                                ->columnSpanFull(),
                                                                        ])
                                                                ])
                                                                ->compact(),
                                                        ])
                                                ]),

                                            Builder\Block::make('image')
                                                ->label('Image')
                                                ->icon('heroicon-o-photo')
                                                ->schema([
                                                    Group::make()
                                                        ->schema([
                                                            Section::make('Image Upload')
                                                                ->schema([
                                                                    Grid::make(1)
                                                                        ->schema([
                                                                            FileUpload::make('src')
                                                                                ->label('Image File')
                                                                                ->disk('public')
                                                                                ->visibility('public')
                                                                                ->directory('posts/images')
                                                                                ->image()
                                                                                ->imageEditor()
                                                                                ->imagePreviewHeight('200')
                                                                                ->maxSize(5120)
                                                                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                                                                ->helperText('Recommended formats: JPG, PNG, WebP. Max size: 5MB')
                                                                                ->required()
                                                                                ->columnSpanFull(),
                                                                        ])
                                                                ])
                                                                ->compact(),
                                                        ])
                                                ]),

                                            Builder\Block::make('ad-banner')
                                                ->label('Advertisement Banner')
                                                ->icon('heroicon-o-megaphone')
                                                ->schema([
                                                    Group::make()
                                                        ->schema([
                                                            Section::make('Banner Configuration')
                                                                ->schema([
                                                                    Grid::make(1)
                                                                        ->schema([
                                                                            TextInput::make('href')
                                                                                ->label('Target URL')
                                                                                ->url()
                                                                                ->placeholder('https://example.com')
                                                                                ->helperText('Where should this banner link to?')
                                                                                ->columnSpanFull(),
                                                                        ])
                                                                ])
                                                                ->compact(),

                                                            Section::make('Banner Images')
                                                                ->description('Upload images for light and dark themes')
                                                                ->schema([
                                                                    Grid::make(1)
                                                                        ->schema([
                                                                            Repeater::make('images')
                                                                                ->label('Theme Images')
                                                                                ->schema([
                                                                                    FileUpload::make('path')
                                                                                        ->label('Banner Image')
                                                                                        ->image()
                                                                                        ->disk('public')
                                                                                        ->visibility('public')
                                                                                        ->directory('posts/images')
                                                                                        ->imageEditor()
                                                                                        ->imageEditorAspectRatios([
                                                                                            '16:9' => '16:9 (Recommended)',
                                                                                            '4:3' => '4:3 (Standard)',
                                                                                            '1:1' => '1:1 (Square)',
                                                                                        ])
                                                                                        ->maxSize(5120)
                                                                                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                                                                        ->helperText('Recommended size: 1200x675px (16:9 ratio)')
                                                                                        ->imagePreviewHeight('200')
                                                                                        ->uploadingMessage('Uploading banner image...')
                                                                                        ->columnSpanFull(),
                                                                                ])
                                                                                ->addActionLabel('Add Theme Variant')
                                                                                ->itemLabel(fn(array $state): ?string => 'Banner Image')
                                                                                ->collapsible()
                                                                                ->columnSpanFull(),
                                                                        ])
                                                                ])
                                                                ->compact(),
                                                        ])
                                                ]),

                                            Builder\Block::make('blockquote')
                                                ->label('Quote Block')
                                                ->icon('heroicon-o-chat-bubble-left-right')
                                                ->schema([
                                                    Group::make()
                                                        ->schema([
                                                            Section::make('Quote Content')
                                                                ->schema([
                                                                    Grid::make(1)
                                                                        ->schema([
                                                                            Textarea::make('quote')
                                                                                ->label('Quote Text')
                                                                                ->required()
                                                                                ->rows(3)
                                                                                ->placeholder('Enter the quote text...')
                                                                                ->columnSpanFull(),

                                                                            TextInput::make('author')
                                                                                ->label('Quote Author')
                                                                                ->placeholder('Author name (optional)')
                                                                                ->columnSpanFull(),
                                                                        ])
                                                                ])
                                                                ->compact(),
                                                        ])
                                                ]),

                                            Builder\Block::make('side-by-side')
                                                ->label('Side-by-Side Content')
                                                ->icon('heroicon-o-rectangle-group')
                                                ->schema([
                                                    Group::make()
                                                        ->schema([
                                                            Section::make('Side-by-Side Layout')
                                                                ->description('Create a two-column layout with image and content')
                                                                ->schema([
                                                                    Grid::make(2)
                                                                        ->schema([
                                                                            FileUpload::make('image')
                                                                                ->label('Featured Image')
                                                                                ->disk('public')
                                                                                ->visibility('public')
                                                                                ->directory('posts/images')
                                                                                ->image()
                                                                                ->imageEditor()
                                                                                ->imagePreviewHeight('200')
                                                                                ->maxSize(5120)
                                                                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                                                                ->helperText('Image for the left side of the layout')
                                                                                ->columnSpan(1),

                                                                            Group::make()
                                                                                ->schema([
                                                                                    TextInput::make('heading')
                                                                                        ->label('Section Heading')
                                                                                        ->placeholder('Enter heading...')
                                                                                        ->columnSpanFull(),

                                                                                    Textarea::make('paragraph')
                                                                                        ->label('Description Text')
                                                                                        ->rows(4)
                                                                                        ->placeholder('Enter description paragraph...')
                                                                                        ->columnSpanFull(),
                                                                                ])
                                                                                ->columnSpan(1),
                                                                        ])
                                                                ])
                                                                ->compact(),

                                                            Section::make('Key Points')
                                                                ->schema([
                                                                    Grid::make(1)
                                                                        ->schema([
                                                                            Repeater::make('list')
                                                                                ->label('Bullet Points')
                                                                                ->schema([
                                                                                    TextInput::make('value')
                                                                                        ->label('List Item')
                                                                                        ->placeholder('Enter bullet point...')
                                                                                        ->columnSpanFull(),
                                                                                ])
                                                                                ->itemLabel(fn(array $state): ?string => '• ' . str($state['value'] ?? '')->limit(40))
                                                                                ->addActionLabel('Add Bullet Point')
                                                                                ->collapsible()
                                                                                ->columnSpanFull(),
                                                                        ])
                                                                ])
                                                                ->compact()
                                                                ->collapsible(),
                                                        ])
                                                ]),
                                        ])
                                        ->blockNumbers(false)
                                        ->addActionLabel('Add Content Block')
                                        ->collapsible()
                                        ->cloneable()
                                        ->columnSpanFull(),
                                ])
                        ])
                        ->collapsible()
                        ->persistCollapsed(),
                ])
                ->columnSpanFull(),
        ]);
    }
}
