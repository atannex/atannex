<?php

namespace App\Filament\Resources\Sections\Schemas;

use App\Enums\Flag;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class SectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->schema([
                        Section::make('Section Details')
                            ->description('Define the core information for this section')
                            ->icon('heroicon-o-squares-2x2')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Section Name')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(
                                                fn ($state, callable $set) => $set('slug', Str::slug($state))
                                            )
                                            ->placeholder('e.g., Technology News')
                                            ->helperText('Enter a descriptive name for this section')
                                            ->prefixIcon('heroicon-o-tag'),

                                        TextInput::make('slug')
                                            ->label('URL Slug')
                                            ->disabled()
                                            ->dehydrated()
                                            ->maxLength(255)
                                            ->placeholder('auto-generated')
                                            ->helperText('Generated automatically from name')
                                            ->prefixIcon('heroicon-o-link')
                                            ->suffixIcon('heroicon-o-lock-closed'),
                                    ]),

                                Textarea::make('description')
                                    ->label('Description')
                                    ->rows(3)
                                    ->maxLength(500)
                                    ->placeholder('Provide a brief description of this section...')
                                    ->helperText('Optional: Helps with SEO and organization')
                                    ->columnSpanFull(),
                            ])
                            ->collapsible()
                            ->columnSpan(['lg' => 2]),
                        Section::make('Configuration')
                            ->description('Manage section settings and behavior')
                            ->icon('heroicon-o-cog-6-tooth')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        Select::make('flag')
                                            ->label('Status Flag')
                                            ->required()
                                            ->default(Flag::PENDING)
                                            ->options(Flag::asSelectArray())
                                            ->searchable()
                                            ->preload()
                                            ->native(false)
                                            ->placeholder('Select status')
                                            ->helperText('Current operational status')
                                            ->prefixIcon('heroicon-o-flag')
                                            ->live()
                                            ->afterStateUpdated(function ($state, callable $set) {
                                                if ($state === 'active') {
                                                    $set('is_featured', true);
                                                }
                                            }),

                                        TextInput::make('order')
                                            ->label('Display Order')
                                            ->numeric()
                                            ->default(0)
                                            ->minValue(0)
                                            ->maxValue(999)
                                            ->step(1)
                                            ->placeholder('0')
                                            ->helperText('Lower numbers appear first')
                                            ->prefixIcon('heroicon-o-arrows-up-down'),
                                    ]),

                                Grid::make(2)
                                    ->schema([
                                        Toggle::make('is_featured')
                                            ->label('Featured Section')
                                            ->helperText('Highlight this section')
                                            ->inline(false)
                                            ->default(false),

                                        Toggle::make('is_visible')
                                            ->label('Publicly Visible')
                                            ->helperText('Show on frontend')
                                            ->inline(false)
                                            ->default(true),
                                    ]),
                            ])
                            ->collapsible()
                            ->columnSpan(['lg' => 2]),
                    ])
                    ->columnSpan(['lg' => 2]),

                Group::make()
                    ->schema([
                        Section::make('Quick Info')
                            ->description('Summary and shortcuts')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                TextEntry::make('preview_url')
                                    ->label('Preview URL')
                                    ->state(fn ($record): string => $record?->slug
                                        ? '/sections/'.$record->slug
                                        : 'Not available')
                                    ->visible(fn ($record) => $record !== null)
                                    ->helperText('Frontend section URL'),

                                TextEntry::make('status_badge')
                                    ->label('Current Status')
                                    ->state(fn ($record): string => $record?->flag
                                        ? ucfirst($record->flag)
                                        : 'Pending')
                                    ->visible(fn ($record) => $record !== null),
                            ])
                            ->collapsible(),

                        Section::make('Metadata')
                            ->description('System timestamps')
                            ->icon('heroicon-o-clock')
                            ->schema([
                                TextEntry::make('created_at')
                                    ->label('Created')
                                    ->state(fn ($record): string => $record?->created_at
                                        ? $record->created_at->format('M j, Y g:i A').
                                        ' ('.$record->created_at->diffForHumans().')'
                                        : '-'),

                                TextEntry::make('updated_at')
                                    ->label('Last Updated')
                                    ->state(fn ($record): string => $record?->updated_at
                                        ? $record->updated_at->format('M j, Y g:i A').
                                        ' ('.$record->updated_at->diffForHumans().')'
                                        : '-'),

                                TextEntry::make('deleted_at')
                                    ->label('Deleted')
                                    ->state(fn ($record): string => $record?->deleted_at
                                        ? $record->deleted_at->format('M j, Y g:i A').
                                        ' ('.$record->deleted_at->diffForHumans().')'
                                        : 'Not deleted')
                                    ->visible(fn ($record) => $record?->deleted_at !== null),
                            ])
                            ->collapsible()
                            ->collapsed()
                            ->visible(fn ($record) => $record !== null),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }
}
