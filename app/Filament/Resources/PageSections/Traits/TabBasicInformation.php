<?php

namespace App\Filament\Resources\PageSections\Traits;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;

/**
 * Class TabBasicInformation
 *
 * Provides the schema definition for the "Basic Information" tab
 * used in page sections within the Filament admin panel.
 */
class TabBasicInformation
{
    /**
     * Creates the schema for the Basic Information tab.
     *
     * @return Section
     */
    public static function make(): Section
    {
        return Section::make('Basic Information')
            ->schema([
                Grid::make(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Tab Title')
                            ->placeholder('e.g., Breaking News, Latest Updates')
                            ->helperText('The display name of this tab in the interface.')
                            ->maxLength(50)
                            ->required(),

                        TextInput::make('limit')
                            ->label('Posts Limit')
                            ->numeric()
                            ->default(5)
                            ->minValue(1)
                            ->maxValue(50)
                            ->suffix('posts')
                            ->helperText('Maximum number of posts to display in this tab.')
                            ->required(),

                        TextInput::make('limit_per_leaf_post')
                            ->label('Limit per Leaf Post')
                            ->numeric()
                            ->default(5)
                            ->minValue(1)
                            ->maxValue(50)
                            ->suffix('posts')
                            ->helperText('Maximum number of posts to display for each child element.')
                            ->required(),

                        Select::make('sort')
                            ->label('Sort By')
                            ->options([
                                'published_at' => 'Published Date',
                                'created_at' => 'Creation Date',
                                'title' => 'Title',
                                'name' => 'Name',
                                'views' => 'Views',
                                'comments_count' => 'Comments Count',
                                'likes_count' => 'Likes Count',
                                'shares_count' => 'Shares Count',
                            ])
                            ->default('created_at')
                            ->helperText('Select the field by which content should be sorted.'),

                        Select::make('order')
                            ->label('Sort Order')
                            ->options([
                                'asc' => 'Ascending',
                                'desc' => 'Descending',
                            ])
                            ->default('desc')
                            ->helperText('Choose the sorting direction.'),
                    ]),
            ])
            ->compact();
    }
}
