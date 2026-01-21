<?php

namespace App\Filament\Resources\PostTags\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;

class PostTagForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Association Details')
                ->description('Link a post with a tag to create a relationship')
                ->icon('heroicon-o-link')
                ->schema([
                    Grid::make(2)->schema([
                        Select::make('post_id')
                            ->label('Post')
                            ->relationship('post', 'title')
                            ->required()
                            ->searchable(['title', 'slug'])
                            ->preload()
                            ->native(false)
                            ->placeholder('Select a post')
                            ->helperText('Choose the post to tag')
                            ->getOptionLabelFromRecordUsing(
                                fn($record) => "{$record->title} (ID: {$record->id})"
                            )
                            ->optionsLimit(50),

                        Select::make('tag_id')
                            ->label('Tag')
                            ->relationship('tag', 'name')
                            ->required()
                            ->searchable(['name', 'slug'])
                            ->preload()
                            ->native(false)
                            ->placeholder('Select a tag')
                            ->helperText('Choose the tag to assign')
                            ->createOptionModalHeading('Create New Tag')
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('slug')
                                    ->disabled()
                                    ->maxLength(255)
                                    ->helperText('URL-friendly version'),

                                Textarea::make('description')
                                    ->rows(3)
                                    ->maxLength(500),
                            ])
                            ->getOptionLabelFromRecordUsing(
                                fn($record) =>
                                $record->name .
                                    ($record->posts_count > 0 ? " ({$record->posts_count} posts)" : '')
                            )
                            ->optionsLimit(50),
                    ]),

                    TextEntry::make('relationship_info')
                        ->label('Information')
                        ->state(
                            'This will create a many-to-many relationship between the selected post and tag.
                             A post may have multiple tags, and a tag may belong to multiple posts.'
                        )
                        ->columnSpanFull()
                        ->extraAttributes(['class' => 'text-sm text-gray-500']),
                ])
                ->columnSpanFull(),

            Section::make('Metadata')
                ->description('Additional information about this association')
                ->icon('heroicon-o-information-circle')
                ->collapsible()
                ->collapsed()
                ->hidden(fn($record) => $record === null)
                ->schema([
                    Grid::make(3)->schema([
                        TextEntry::make('created_at')
                            ->label('Created At')
                            ->state(
                                fn($record) =>
                                $record?->created_at?->format('M j, Y H:i') ?? 'N/A'
                            ),

                        TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->state(
                                fn($record) =>
                                $record?->updated_at?->diffForHumans() ?? 'N/A'
                            ),

                        TextEntry::make('association_id')
                            ->label('Association ID')
                            ->state(
                                fn($record) =>
                                $record?->id ?? 'Will be generated'
                            ),
                    ]),
                ])
                ->columnSpanFull(),
        ]);
    }
}
