<?php

declare(strict_types=1);

namespace App\Filament\Resources\Regions\Schemas;

use App\Enums\Flag;
use App\Enums\Territories;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RegionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            /**
             * =========================
             * IDENTITY GROUP
             * =========================
             */
            Group::make()
                ->schema([

                    Section::make('Identity')
                        ->description('Core identification details for the region')
                        ->icon('heroicon-o-globe-alt')
                        ->schema([

                            TextInput::make('name')
                                ->label('Region Name')
                                ->placeholder('e.g. Central Region')
                                ->required()
                                ->maxLength(255)
                                ->autofocus()
                                ->columnSpan(1),

                            TextInput::make('slug')
                                ->label('Slug')
                                ->placeholder('e.g. central-region')
                                ->required()
                                ->regex('/^[a-z0-9-]+$/')
                                ->disabled()
                                // ->helperText('Only lowercase letters, numbers, and hyphens are allowed')
                                ->maxLength(255)
                                ->columnSpan(1),
                        ]),
                ]),

            /**
             * =========================
             * CLASSIFICATION GROUP
             * =========================
             */
            Group::make()
                ->schema([

                    Section::make('Classification')
                        ->description('Territory level and publication status')
                        ->icon('heroicon-o-tag')
                        ->schema([

                            Select::make('territory')
                                ->label('Territory Level')
                                ->options(Territories::asSelectArray())
                                ->required()
                                ->native(false)
                                ->searchable()
                                ->preload()
                                ->columnSpan(1),

                            Select::make('flag')
                                ->label('Status')
                                ->options(Flag::asSelectArray())
                                ->default(Flag::DRAFT)
                                ->required()
                                ->native(false)
                                ->searchable()
                                ->preload()
                                ->columnSpan(1),
                        ]),
                ]),

            /**
             * =========================
             * ORGANIZATION GROUP
             * =========================
             */
            Group::make()
                ->schema([

                    Section::make('Organization')
                        ->description('Hierarchy, ordering, and parent relationships')
                        ->icon('heroicon-o-bars-3')
                        ->columns(3)
                        ->schema([

                            TextInput::make('position')
                                ->label('Display Order')
                                ->numeric()
                                ->default(0)
                                ->minValue(0)
                                // ->helperText('Lower values appear first')
                                ->columnSpan(1),

                            TextInput::make('slug_path')
                                ->label('Hierarchy Path')
                                ->disabled()
                                ->dehydrated(false)
                                ->placeholder('Auto-generated')
                                ->columnSpan(2),

                            Select::make('parent_id')
                                ->label('Parent Region')
                                ->relationship('parent', 'name')
                                ->searchable()
                                ->preload()
                                ->native(false)
                                ->placeholder('None (Top-level)')
                                ->columnSpanFull(),
                        ]),
                ]),

            /**
             * =========================
             * DETAILS GROUP
             * =========================
             */
            Group::make()
                ->schema([

                    Section::make('Details')
                        ->description('Optional extended information')
                        ->icon('heroicon-o-document-text')
                        ->columns(1)
                        ->schema([

                            Textarea::make('description')
                                ->label('Description')
                                ->placeholder('Enter detailed information about this region...')
                                ->rows(6)
                                ->maxLength(2000)
                                ->columnSpanFull(),
                        ]),
                ]),
        ]);
    }
}
