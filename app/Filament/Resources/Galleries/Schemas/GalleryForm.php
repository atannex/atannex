<?php

namespace App\Filament\Resources\Galleries\Schemas;

use App\Enums\Flag;
use App\Enums\Image;
use App\Enums\TailwindColor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;

class GalleryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Group::make()
                ->schema([
                    Grid::make(['default' => 1, 'md' => 4, 'lg' => 4])
                        ->schema([
                            Group::make()
                                ->schema([
                                    Section::make('Configuration')
                                        ->icon('heroicon-o-cog-6-tooth')
                                        ->description('Gallery settings and properties')
                                        ->schema([
                                            Select::make('type')
                                                ->label('Gallery Type')
                                                ->options(Image::asSelectArray())
                                                ->required()
                                                ->searchable()
                                                ->preload()
                                                ->native(false)
                                                ->default(Image::LOGO)
                                                ->helperText('Choose the category for this gallery item'),

                                            Select::make('flag')
                                                ->label('Review Status')
                                                ->options(Flag::asSelectArray())
                                                ->required()
                                                ->default(Flag::PENDING_REVIEW)
                                                ->native(false)
                                                ->helperText('Set the current review status'),

                                            Select::make('color')
                                                ->label('Theme Color')
                                                ->options(TailwindColor::asSelectArray())
                                                ->nullable()
                                                ->searchable()
                                                ->preload()
                                                ->native(false)
                                                ->helperText('Optional accent color for styling'),

                                            TextInput::make('order')
                                                ->label('Display Order')
                                                ->numeric()
                                                ->default(0)
                                                ->minValue(0)
                                                ->helperText('Lower numbers appear first in the gallery'),
                                        ])
                                        ->columnSpanFull(),
                                ])
                                ->columnSpan(['default' => 1, 'md' => 2, 'lg' => 2]),

                            Group::make()
                                ->schema([
                                    Section::make('Title')
                                        ->icon('heroicon-o-tag')
                                        ->description('Gallery name')
                                        ->schema([
                                            TextInput::make('title')
                                                ->label('Gallery Title')
                                                ->placeholder('e.g., Summer Collection 2024')
                                                ->required()
                                                ->maxLength(255)
                                                ->helperText('Give your gallery a descriptive title')
                                                ->hiddenLabel(),
                                        ])
                                        ->columnSpanFull()
                                        ->compact(),
                                    Section::make('Image')
                                        ->icon('heroicon-o-arrow-up-tray')
                                        ->description('Upload and edit')
                                        ->schema([
                                            FileUpload::make('image')
                                                ->disk('public')
                                                ->directory('gallery')
                                                ->visibility('public')
                                                ->image()
                                                ->imageEditor()
                                                ->imageEditorAspectRatioOptions([
                                                    '16:9' => '16:9 (Landscape)',
                                                    '4:3' => '4:3 (Standard)',
                                                    '1:1' => '1:1 (Square)',
                                                ])
                                                ->maxSize(5120)
                                                ->acceptedFileTypes([
                                                    'image/jpeg',
                                                    'image/png',
                                                    'image/webp',
                                                    'image/svg+xml',
                                                ])
                                                ->imagePreviewHeight('300')
                                                ->dehydrateStateUsing(function ($state, $record) {
                                                    if ($record && $record->image && $record->image !== $state) {
                                                        Storage::disk('public')->delete($record->image);
                                                    }
                                                    return $state;
                                                })
                                                ->required()
                                                ->hiddenLabel(),
                                        ])
                                        ->columnSpanFull()
                                        ->compact(),
                                    Section::make('Description')
                                        ->icon('heroicon-o-document-text')
                                        ->description('Details about this item')
                                        ->collapsible()
                                        ->collapsed(true)
                                        ->schema([
                                            Textarea::make('description')
                                                ->label('Description')
                                                ->placeholder('Enter optional details about this gallery item...')
                                                ->rows(4)
                                                ->maxLength(500)
                                                ->helperText('500 characters max')
                                                ->hiddenLabel(),
                                        ])
                                        ->columnSpanFull()
                                        ->compact(),
                                ])
                                ->columnSpan(['default' => 1, 'md' => 2, 'lg' => 2]),
                        ])
                        ->columnSpanFull(),
                ])
                ->columnSpanFull(),
        ]);
    }
}
