<?php

namespace App\Filament\Resources\Reviews\Schemas;

use App\Enums\Flag;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->schema([
                        Section::make('Reviewable Entity')
                            ->description('Specify the entity being reviewed')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('reviewable_type')
                                            ->label('Entity Type')
                                            ->required()
                                            ->maxLength(255)
                                            ->placeholder('e.g., Product, Service, etc.')
                                            ->helperText('The type of entity being reviewed'),

                                        TextInput::make('reviewable_id')
                                            ->label('Entity ID')
                                            ->required()
                                            ->numeric()
                                            ->minValue(1)
                                            ->placeholder('Enter entity ID')
                                            ->helperText('The unique identifier of the entity'),
                                    ]),
                            ])
                            ->collapsible()
                            ->persistCollapsed(),

                        Section::make('Review Content')
                            ->description('The main review content and rating')
                            ->icon('heroicon-o-star')
                            ->schema([
                                Grid::make(1)
                                    ->schema([
                                        Textarea::make('content')
                                            ->label('Review Content')
                                            ->required()
                                            ->rows(8)
                                            ->maxLength(5000)
                                            ->placeholder('Write the review content here...')
                                            ->helperText('Maximum 5000 characters')
                                            ->columnSpanFull(),
                                    ]),
                            ])
                            ->collapsible(),

                        Section::make('Reviewer Information')
                            ->description('Details about the reviewer')
                            ->icon('heroicon-o-user')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        Select::make('user_id')
                                            ->label('Registered User')
                                            ->relationship('user', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->placeholder('Select a user (optional)')
                                            ->helperText('Link to a registered user account')
                                            ->native(false),

                                        TextInput::make('reviewer_name')
                                            ->label('Guest Name')
                                            ->maxLength(255)
                                            ->placeholder('Enter name for guest reviewers')
                                            ->helperText('Use when no user account exists'),
                                    ]),
                            ])
                            ->collapsible()
                            ->persistCollapsed(),
                    ])
                    ->columnSpan(['lg' => 2]),

                Group::make()
                    ->schema([
                        Section::make('Review Status')
                            ->description('Manage review approval')
                            ->icon('heroicon-o-flag')
                            ->schema([
                                Grid::make(1)
                                    ->schema([
                                        Select::make('flag')
                                            ->label('Status')
                                            ->options(Flag::asSelectArray())
                                            ->preload()
                                            ->searchable()
                                            ->required()
                                            ->default(Flag::PENDING_REVIEW)
                                            ->native(false)
                                            ->helperText('Set the review status'),
                                    ]),
                            ]),

                        Section::make('Ratings')
                            ->description('User rating for the reviewable entity')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                TextInput::make('reviewer_rating')
                                    ->label('Rating')
                                    ->required()
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(5)
                                    ->step(0.5)
                                    ->suffix('/ 5')
                                    ->placeholder('0.0')
                                    ->helperText('Rating from 1 to 5'),
                            ]),

                        Section::make('Metadata')
                            ->description('Tracking information')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Grid::make(1)
                                    ->schema([
                                        TextInput::make('ip_address')
                                            ->label('IP Address')
                                            ->placeholder('Auto-detected')
                                            ->helperText('Reviewer IP address')
                                            ->disabled()
                                            ->dehydrated(true),
                                    ]),
                            ])
                            ->collapsible()
                            ->collapsed()
                            ->persistCollapsed(),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }
}
