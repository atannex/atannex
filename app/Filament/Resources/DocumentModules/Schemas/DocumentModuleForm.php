<?php

namespace App\Filament\Resources\DocumentModules\Schemas;

use App\Enums\Flag;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;

class DocumentModuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Group::make()
                ->schema([
                    Section::make('Module Configuration')
                        ->description('Essential settings for this document module')
                        ->icon('heroicon-o-cog-6-tooth')
                        ->schema([
                            Grid::make(2)
                                ->schema([
                                    Select::make('document_id')
                                        ->label('Associated Document')
                                        ->relationship('document', 'title')
                                        ->searchable()
                                        ->preload()
                                        ->placeholder('Search and select a document')
                                        ->helperText('Connect this module to an existing document')
                                        ->required()
                                        ->native(false)
                                        ->prefixIcon('heroicon-o-document-text')
                                        ->columnSpan(1),

                                    Select::make('flag')
                                        ->label('Status')
                                        ->options(Flag::asSelectArray())
                                        ->default(Flag::DRAFT)
                                        ->preload()
                                        ->searchable()
                                        ->required()
                                        ->native(false)
                                        ->prefixIcon('heroicon-o-flag')
                                        ->helperText('Set the publication status')
                                        ->columnSpan(1),
                                ]),
                        ])
                        ->collapsible()
                        ->persistCollapsed(),
                ])
                ->columnSpanFull(),

            Group::make()
                ->schema([
                    Section::make('Document Header')
                        ->description('Featured image for the entire document')
                        ->icon('heroicon-o-photo')
                        ->schema([
                            Grid::make(2)
                                ->schema([
                                    Group::make()
                                        ->schema([
                                            Section::make('Header Image')
                                                ->schema([
                                                    Grid::make(1)
                                                        ->schema([
                                                            FileUpload::make('header_image')
                                                                ->label('Document Cover Image')
                                                                ->disk('public')
                                                                ->visibility('public')
                                                                ->directory('documents/headers')
                                                                ->image()
                                                                ->imageEditor()
                                                                ->imageEditorAspectRatios([
                                                                    null => 'Free Form',
                                                                    '16:9' => '16:9 (Landscape)',
                                                                    '21:9' => '21:9 (Ultra Wide)',
                                                                    '4:3' => '4:3 (Standard)',
                                                                    '3:2' => '3:2 (Classic)',
                                                                    '1:1' => '1:1 (Square)',
                                                                ])
                                                                ->imagePreviewHeight('320')
                                                                ->maxSize(5120)
                                                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'])
                                                                ->helperText('Main cover image for the document • Max: 5MB')
                                                                ->panelLayout('integrated')
                                                                ->afterStateUpdated(function ($state, $record) {
                                                                    if ($record && $record->header_image && $record->header_image !== $state) {
                                                                        Storage::disk('public')->delete($record->header_image);
                                                                    }
                                                                })
                                                                ->columnSpanFull(),
                                                        ]),
                                                ])
                                                ->compact()
                                                ->hiddenLabel(),
                                        ])
                                        ->columnSpan(1),

                                    Group::make()
                                        ->schema([
                                            Section::make('Image Metadata')
                                                ->schema([
                                                    Grid::make(1)
                                                        ->schema([
                                                            TextInput::make('header_image_alt')
                                                                ->label('Alt Text (SEO)')
                                                                ->placeholder('Describe the header image')
                                                                ->maxLength(255)
                                                                ->helperText('Improve accessibility and SEO')
                                                                ->suffixIcon('heroicon-o-eye')
                                                                ->columnSpanFull(),

                                                            TextInput::make('header_image_caption')
                                                                ->label('Image Caption')
                                                                ->placeholder('Add a caption for the header')
                                                                ->maxLength(500)
                                                                ->helperText('Optional caption text')
                                                                ->suffixIcon('heroicon-o-chat-bubble-bottom-center-text')
                                                                ->columnSpanFull(),

                                                            TextInput::make('header_image_credit')
                                                                ->label('Image Credit')
                                                                ->placeholder('Photographer or source')
                                                                ->maxLength(255)
                                                                ->helperText('Attribution information')
                                                                ->suffixIcon('heroicon-o-user-circle')
                                                                ->columnSpanFull(),
                                                        ]),
                                                ])
                                                ->compact()
                                                ->hiddenLabel(),
                                        ])
                                        ->columnSpan(1),
                                ]),
                        ])
                        ->collapsible()
                        ->collapsed()
                        ->persistCollapsed(),
                ])
                ->columnSpanFull(),

            Group::make()
                ->schema([
                    Section::make('Document Sections')
                        ->description('Build your document with structured sections')
                        ->icon('heroicon-o-document-duplicate')
                        ->schema([
                            Grid::make(1)
                                ->schema([
                                    Repeater::make('content')
                                        ->label('Content Sections')
                                        ->schema([
                                            Grid::make(2)
                                                ->schema([
                                                    Group::make()
                                                        ->schema([
                                                            Section::make('Section Image')
                                                                ->schema([
                                                                    Grid::make(1)
                                                                        ->schema([
                                                                            FileUpload::make('image')
                                                                                ->label('Feature Image')
                                                                                ->disk('public')
                                                                                ->visibility('public')
                                                                                ->directory('documents/sections')
                                                                                ->image()
                                                                                ->imageEditor()
                                                                                ->imageEditorAspectRatios([
                                                                                    null => 'Free Form',
                                                                                    '16:9' => '16:9 (Landscape)',
                                                                                    '4:3' => '4:3 (Standard)',
                                                                                    '1:1' => '1:1 (Square)',
                                                                                    '3:2' => '3:2 (Classic)',
                                                                                    '9:16' => '9:16 (Portrait)',
                                                                                ])
                                                                                ->imagePreviewHeight('280')
                                                                                ->maxSize(5120)
                                                                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'])
                                                                                ->helperText('JPG, PNG, WebP, SVG • Max: 5MB')
                                                                                ->panelLayout('integrated')
                                                                                ->afterStateUpdated(function ($state, $record) {
                                                                                    if ($record && $record->image && $record->image !== $state) {
                                                                                        Storage::disk('public')->delete($record->image);
                                                                                    }
                                                                                })
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
                                                                            TextInput::make('image_alt')
                                                                                ->label('Alt Text (SEO)')
                                                                                ->placeholder('Describe the image for accessibility')
                                                                                ->maxLength(255)
                                                                                ->helperText('Important for accessibility and SEO')
                                                                                ->suffixIcon('heroicon-o-eye')
                                                                                ->columnSpanFull(),

                                                                            TextInput::make('image_caption')
                                                                                ->label('Image Caption')
                                                                                ->placeholder('Add a descriptive caption')
                                                                                ->maxLength(500)
                                                                                ->helperText('Displayed beneath the image')
                                                                                ->suffixIcon('heroicon-o-chat-bubble-bottom-center-text')
                                                                                ->columnSpanFull(),

                                                                            TextInput::make('image_credit')
                                                                                ->label('Image Credit')
                                                                                ->placeholder('Photographer or source')
                                                                                ->maxLength(255)
                                                                                ->helperText('Attribution for the image')
                                                                                ->suffixIcon('heroicon-o-user-circle')
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
                                                    TextInput::make('title')
                                                        ->label('Section Title')
                                                        ->placeholder('Enter section title')
                                                        ->required()
                                                        ->maxLength(255)
                                                        ->suffixIcon('heroicon-o-bookmark')
                                                        ->helperText('Main title for this section')
                                                        ->columnSpanFull(),

                                                    TextInput::make('heading')
                                                        ->label('Section Heading')
                                                        ->placeholder('Enter section heading')
                                                        ->required()
                                                        ->maxLength(255)
                                                        ->suffixIcon('heroicon-o-h1')
                                                        ->helperText('Subheading or subtitle')
                                                        ->columnSpanFull(),

                                                    Textarea::make('description')
                                                        ->label('Section Description')
                                                        ->placeholder('Provide detailed content for this section...')
                                                        ->rows(6)
                                                        ->required()
                                                        ->helperText('The main content of this section')
                                                        ->columnSpanFull(),
                                                ]),
                                        ])
                                        ->addActionLabel('+ Add Section')
                                        ->collapsible()
                                        ->cloneable()
                                        ->reorderable()
                                        ->reorderableWithButtons()
                                        ->itemLabel(fn (array $state): ?string => '📄 '.($state['title'] ?? 'Section'))
                                        ->defaultItems(1)
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
