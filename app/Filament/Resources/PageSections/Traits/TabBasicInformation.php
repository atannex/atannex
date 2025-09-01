<?php

namespace App\Filament\Resources\PageSections\Traits;

use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class TabBasicInformation
{
    /**
     * Get the schema for the Tab Basic Information section.
     *
     * @return \Filament\Forms\Components\Section
     */
    public static function make(): Section
    {
        return Section::make('Tab Basic Information')
            ->schema([
                Grid::make(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Tab Title')
                            ->placeholder('e.g., Breaking News, Latest Updates')
                            ->helperText('Display name for this tab')
                            ->maxLength(50)
                            ->required(),

                        TextInput::make('limit')
                            ->label('Content Limit')
                            ->numeric()
                            ->default(5)
                            ->minValue(1)
                            ->maxValue(50)
                            ->suffix('posts')
                            ->helperText('Maximum number of posts to display')
                            ->required(),
                        TextInput::make('post_limit')
                            ->label('Category Post Limit')
                            ->numeric()
                            ->default(5)
                            ->minValue(1)
                            ->maxValue(50)
                            ->suffix('posts')
                            ->helperText('Maximum number of posts to display')
                            ->required(),
                        TextInput::make('leaf_post_limit')
                            ->label('leaf_post_limit')
                            ->numeric()
                            ->default(5)
                            ->minValue(1)
                            ->maxValue(50)
                            ->suffix('posts')
                            ->helperText('Maximum number of posts to display')
                            ->required(),

                        Select::make('sort')
                            ->label('Sort By')
                            ->options([
                                'published_at' => 'Published At',
                                'created_at' => 'Created At',
                                'title' => 'Title',
                                'name' => 'Name',
                                'views' => 'Views',
                                'comments_count' => 'Comments Count',
                                'likes_count' => 'Likes Count',
                                'shares_count' => 'Shares Count',
                            ])
                            ->default('created_at')
                            ->helperText('Field to sort the content by'),

                        Select::make('order')
                            ->label('Sort Order')
                            ->options([
                                'asc' => 'Ascending',
                                'desc' => 'Descending',
                            ])
                            ->default('desc')
                            ->helperText('Sorting direction'),

                    ]),
            ])
            ->compact();
    }
}
