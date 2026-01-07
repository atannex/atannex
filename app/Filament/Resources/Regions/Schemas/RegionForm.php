<?php

namespace App\Filament\Resources\Regions\Schemas;

use App\Enums\Flag;
use App\Enums\Territories;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class RegionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->schema([
                        Section::make('Basic Information')
                            ->description('Enter the core details for this region')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Region Name')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(
                                                fn($state, callable $set) => $set('slug', Str::slug($state))
                                            )
                                            ->placeholder('e.g., North America')
                                            ->helperText('This will be the primary display name')
                                            ->columnSpan(1),

                                        TextInput::make('slug')
                                            ->label('URL Slug')
                                            ->disabled()
                                            ->dehydrated()
                                            ->maxLength(255)
                                            ->placeholder('auto-generated')
                                            ->helperText('Automatically generated from name')
                                            ->prefixIcon('heroicon-o-link')
                                            ->columnSpan(1),
                                    ]),

                                Textarea::make('description')
                                    ->label('Description')
                                    ->rows(3)
                                    ->maxLength(1000)
                                    ->placeholder('Provide a brief description of this region...')
                                    ->helperText('Optional: Add context about this region')
                                    ->columnSpanFull(),
                            ])
                            ->collapsible()
                            ->columnSpan(['lg' => 2]),
                        Section::make('Classification & Hierarchy')
                            ->description("Define the region's status and relationships")
                            ->icon('heroicon-o-tag')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        Select::make('flag')
                                            ->label('Status Flag')
                                            ->required()
                                            ->options(Flag::asSelectArray())
                                            ->searchable()
                                            ->preload()
                                            ->default(Flag::DRAFT)
                                            ->native(false)
                                            ->placeholder('Select a status')
                                            ->helperText('Current operational status')
                                            ->prefixIcon('heroicon-o-flag')
                                            ->columnSpan(1),

                                        Select::make('territory')
                                            ->label('Territory Type')
                                            ->options(Territories::asSelectArray())
                                            ->default(Territories::QUARTER)
                                            ->searchable()
                                            ->preload()
                                            ->native(false)
                                            ->placeholder('Select territory type')
                                            ->helperText('Geographical classification')
                                            ->prefixIcon('heroicon-o-globe-americas')
                                            ->columnSpan(1),
                                    ]),

                                Select::make('parent_id')
                                    ->label('Parent Region')
                                    ->relationship('parent', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->native(false)
                                    ->placeholder('Select parent (optional)')
                                    ->helperText('Leave empty for top-level regions')
                                    ->prefixIcon('heroicon-o-folder-open')
                                    ->columnSpanFull(),
                            ])
                            ->collapsible()
                            ->columnSpan(['lg' => 2]),
                    ])
                    ->columnSpan(['lg' => 2]),

                Group::make()
                    ->schema([
                        Section::make('Media & Assets')
                            ->description('Upload visual elements')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                FileUpload::make('logo')
                                    ->label('Region Logo')
                                    ->image()
                                    ->imageEditor()
                                    ->imageEditorAspectRatios([
                                        '1:1',
                                        '16:9',
                                    ])
                                    ->maxSize(2048)
                                    ->disk('public')
                                    ->directory(fn($record) => $record?->dir() ?? 'regions/logos')
                                    ->visibility('public')
                                    ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/svg+xml'])
                                    ->helperText('PNG, JPG, or SVG. Max 2MB.')
                                    ->columnSpanFull(),
                            ])
                            ->collapsible(),
                        Section::make('Metadata')
                            ->description('System information')
                            ->icon('heroicon-o-clock')
                            ->schema([
                                TextEntry::make('created_at')
                                    ->label('Created At')
                                    ->state(fn($record): string => $record?->created_at
                                        ? $record->created_at->diffForHumans()
                                        : '-')
                                    ->visible(fn($record) => $record !== null),

                                TextEntry::make('updated_at')
                                    ->label('Last Updated')
                                    ->state(fn($record): string => $record?->updated_at
                                        ? $record->updated_at->diffForHumans()
                                        : '-')
                                    ->visible(fn($record) => $record !== null),

                                TextEntry::make('slug_path')
                                    ->label('Full Path')
                                    ->state(fn($record): string => $record?->slug_path ?? '-')
                                    ->visible(fn($record) => $record !== null && $record->slug_path),
                            ])
                            ->collapsible()
                            ->collapsed()
                            ->visible(fn($record) => $record !== null),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }
}
