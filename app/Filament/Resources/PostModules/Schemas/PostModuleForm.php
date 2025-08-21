<?php

namespace App\Filament\Resources\PostModules\Schemas;

use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class PostModuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('post_id')
                ->relationship('post', 'title')
                ->default(null),
            Builder::make('module_content')
                ->label('Content Sections')
                ->columnSpanFull()
                ->blocks([

                    Builder\Block::make('paragraphs')
                        ->label('Paragraphs')
                        ->schema([
                            Repeater::make('content')
                                ->label('Paragraphs')
                                ->schema([
                                    Textarea::make('value')->label('Text')->rows(3),
                                ])
                                ->itemLabel(fn(array $state): ?string => str($state['value'] ?? '')->limit(30)),
                        ]),

                    Builder\Block::make('heading')
                        ->label('Heading')
                        ->schema([
                            TextInput::make('title')->required(),
                        ]),

                    Builder\Block::make('image')
                        ->label('Image')
                        ->schema([
                            FileUpload::make('src')
                                ->label('Image')
                                ->disk('public')
                                ->visibility('public')
                                ->directory('posts/images')
                                ->image()
                                ->required(),
                        ]),

                    Builder\Block::make('ad-banner')
                        ->label('Ad Banner')
                        ->schema([
                            TextInput::make('href')->label('Target URL'),
                            Repeater::make('images')
                                ->label('Images (light/dark)')
                                ->schema([

                                    FileUpload::make('path')
                                        ->label('Image')
                                        ->image()
                                        ->disk('public')
                                        ->visibility('public')
                                        ->directory('posts/images')
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
                                ->columns(2)
                                ->itemLabel(fn(array $state): ?string => ucfirst($state['mode'] ?? '')),
                        ]),

                    Builder\Block::make('blockquote')
                        ->label('Blockquote')
                        ->schema([
                            Textarea::make('quote')->required(),
                            TextInput::make('author'),
                        ]),

                    Builder\Block::make('side-by-side')
                        ->label('Side by Side Block')
                        ->schema([
                            FileUpload::make('image')
                                ->label('Image')
                                ->disk('public')
                                ->visibility('public')
                                ->directory('posts/images')
                                ->image(),

                            TextInput::make('heading')->label('Heading'),

                            Textarea::make('paragraph')->label('Paragraph')->rows(3),

                            Repeater::make('list')
                                ->label('Bullet Points')
                                ->schema([
                                    TextInput::make('value')->label('List Item'),
                                ])
                                ->itemLabel(fn(array $state): ?string => str($state['value'] ?? '')->limit(30)),
                        ]),
                ]),
        ]);
    }
}
