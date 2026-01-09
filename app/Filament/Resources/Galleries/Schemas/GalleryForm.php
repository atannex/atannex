<?php

namespace App\Filament\Resources\Galleries\Schemas;

use App\Enums\Flag;
use App\Enums\Image;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;

class GalleryForm
{
    /**
     * Configure and return a Filament form Schema for gallery image management.
     *
     * Populates the provided Schema with a responsive two-column layout containing:
     * - Image Configuration (type, original filename),
     * - Status Management (publication status),
     * - Image Upload (file upload with editor and validations).
     *
     * @param Schema $schema The Schema instance to configure.
     * @return Schema The configured Schema containing gallery form components.
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(['default' => 1, 'lg' => 2])
                    ->schema([
                        Group::make()
                            ->schema([
                                Section::make('Image Configuration')
                                    ->description('Define image type and classification')
                                    ->icon('heroicon-o-cog-6-tooth')
                                    ->schema([
                                        Grid::make(1)
                                            ->schema([
                                                Select::make('type')
                                                    ->label('Image Type')
                                                    ->options(Image::asSelectArray())
                                                    ->searchable()
                                                    ->required()
                                                    ->preload()
                                                    ->native(false)
                                                    ->default(Image::LOGO)
                                                    ->prefixIcon('heroicon-o-tag')
                                                    ->helperText('Categorize this image by its intended use')
                                                    ->columnSpanFull(),

                                                TextInput::make('original_name')
                                                    ->label('Original Filename')
                                                    ->placeholder('Auto-captured from upload')
                                                    ->default(null)
                                                    ->dehydrated()
                                                    ->disabled()
                                                    ->prefixIcon('heroicon-o-document')
                                                    ->helperText('Original name preserved for reference')
                                                    ->columnSpanFull(),
                                            ]),
                                    ])
                                    ->compact()
                                    ->collapsible()
                                    ->persistCollapsed(),

                                Section::make('Status Management')
                                    ->description('Control visibility and publication status')
                                    ->icon('heroicon-o-flag')
                                    ->schema([
                                        Grid::make(1)
                                            ->schema([
                                                Select::make('flag')
                                                    ->label('Publication Status')
                                                    ->options(Flag::asSelectArray())
                                                    ->searchable()
                                                    ->required()
                                                    ->preload()
                                                    ->native(false)
                                                    ->default(Flag::DRAFT)
                                                    ->prefixIcon('heroicon-o-flag')
                                                    ->helperText('Current publication state of this image')
                                                    ->columnSpanFull(),
                                            ]),
                                    ])
                                    ->compact()
                                    ->collapsible()
                                    ->persistCollapsed(),
                            ])
                            ->columnSpan(['default' => 1, 'lg' => 1]),

                        Group::make()
                            ->schema([
                                Section::make('Image Upload')
                                    ->description('Upload and manage your gallery image')
                                    ->icon('heroicon-o-photo')
                                    ->schema([
                                        Grid::make(1)
                                            ->schema([
                                                FileUpload::make('image')
                                                    ->label('Gallery Image')
                                                    ->disk('public')
                                                    ->visibility('public')
                                                    ->directory('gallery')
                                                    ->image()
                                                    ->imageEditor()

                                                    ->afterStateUpdated(function ($state, $record) {
                                                        if ($record && $record->image && $record->image !== $state) {
                                                            Storage::disk('public')->delete($record->image);
                                                        }
                                                    })
                                                    ->imageEditorAspectRatios([
                                                        '16:9' => '16:9 (Widescreen)',
                                                        '4:3' => '4:3 (Standard)',
                                                        '1:1' => '1:1 (Square)',
                                                        '3:2' => '3:2 (Classic)',
                                                        '21:9' => '21:9 (Ultrawide)',
                                                    ])
                                                    ->maxSize(5120)
                                                    ->acceptedFileTypes([
                                                        'image/jpeg',
                                                        'image/png',
                                                        'image/jpg',
                                                        'image/webp',
                                                        'image/gif',
                                                        'image/svg+xml',
                                                    ])
                                                    ->helperText('Recommended: 1920×1080px (16:9) | Max size: 5MB | Formats: JPG, PNG, WebP, GIF, SVG')
                                                    ->imagePreviewHeight('320')
                                                    ->panelLayout('integrated')
                                                    ->panelAspectRatio('16:9')
                                                    ->uploadingMessage('Uploading your image...')
                                                    ->removeUploadedFileButtonPosition('top-right')
                                                    ->uploadProgressIndicatorPosition('center')
                                                    ->loadingIndicatorPosition('center')
                                                    ->required()
                                                    ->columnSpanFull(),
                                            ]),
                                    ])
                                    ->collapsible()
                                    ->persistCollapsed(),
                            ])
                            ->columnSpan(['default' => 1, 'lg' => 1]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}