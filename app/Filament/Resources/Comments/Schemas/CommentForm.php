<?php

namespace App\Filament\Resources\Comments\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DateTimePicker;

class CommentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->schema([
                        Section::make('Context')
                            ->description('Defines where and how the comment is attached')
                            ->schema([
                                Grid::make(3)
                                    ->schema([
                                        TextInput::make('commentable_type')
                                            ->label('Model Type')
                                            ->required()
                                            ->placeholder('App\Models\Post'),

                                        TextInput::make('commentable_id')
                                            ->label('Model ID')
                                            ->numeric()
                                            ->required(),

                                        Select::make('parent_id')
                                            ->label('Parent Comment')
                                            ->relationship('parent', 'id')
                                            ->searchable()
                                            ->preload(),
                                    ]),
                            ]),
                        Section::make('Comment')
                            ->schema([
                                Grid::make()
                                    ->schema([
                                        Textarea::make('comment')
                                            ->required()
                                            ->rows(6)
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                    ])
                    ->columnSpan(2),
                Group::make()
                    ->schema([
                        Section::make('Author')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        Toggle::make('is_guest')
                                            ->label('Guest')
                                            ->reactive(),

                                        Select::make('user_id')
                                            ->label('Registered User')
                                            ->relationship('user', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->visible(fn($get) => ! $get('is_guest')),

                                        TextInput::make('guest_name')
                                            ->label('Guest Name')
                                            ->required(fn($get) => $get('is_guest'))
                                            ->visible(fn($get) => $get('is_guest'))
                                            ->columnSpanFull(),

                                        TextInput::make('guest_email')
                                            ->label('Guest Email')
                                            ->email()
                                            ->visible(fn($get) => $get('is_guest'))
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                        Section::make('Engagement')
                            ->schema([
                                Grid::make(3)
                                    ->schema([
                                        TextInput::make('reply_count')
                                            ->numeric()
                                            ->default(0)
                                            ->disabled(),

                                        TextInput::make('like_count')
                                            ->numeric()
                                            ->default(0),

                                        TextInput::make('dislike_count')
                                            ->numeric()
                                            ->disabled()
                                            ->default(0),
                                    ]),
                            ]),
                        Section::make('System')
                            ->collapsed()
                            ->schema([
                                Grid::make()
                                    ->schema([
                                        TextInput::make('comment_hash')
                                            ->label('Hash')
                                            ->disabled()
                                            ->columnSpanFull(),

                                        TextInput::make('ip_address')
                                            ->label('IP Address')
                                            ->disabled()
                                            ->columnSpanFull(),

                                        DateTimePicker::make('edited_at')
                                            ->label('Edited At')
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                    ])
                    ->columnSpan(1),
            ])
            ->columns(3);
    }
}
