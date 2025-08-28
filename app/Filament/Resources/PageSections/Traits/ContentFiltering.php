<?php

namespace App\Filament\Resources\PageSections\Traits;

use App\Enums\PostType;
use App\Models\Tags\Tag;
use App\Models\Pages\Category;
use App\Models\Regions\Region;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;

class ContentFiltering
{
    /**
     * Get the schema for the Content Filtering section.
     *
     * @return \Filament\Forms\Components\Section
     */
    public static function make(): Section
    {
        return Section::make('Content Filtering')
            ->schema([
                Grid::make(1)
                    ->schema([
                        Select::make('type')
                            ->label('Content Type')
                            ->options(PostType::labels())
                            ->live()
                            ->placeholder('Choose content type')
                            ->helperText('Determines how posts are fetched')
                            ->required(),

                        Select::make('fondom_region_id')
                            ->label('Filter by Fondom')
                            ->options(fn() => Region::where('type', 'Fondom')->pluck('name', 'id')->toArray())
                            ->searchable()
                            ->multiple()
                            ->placeholder('Select a specific region')
                            ->helperText('Show only posts with this Fondom')
                            ->preload()
                            ->visible(fn($get) => $get('type') === PostType::POST_BY_FONDOM),

                        Select::make('subdivision_region_id')
                            ->label('Filter by Sub-Division')
                            ->options(fn() => Region::where('type', 'Sub-Division')->pluck('name', 'id')->toArray())
                            ->searchable()
                            ->multiple()
                            ->placeholder('Select a specific region')
                            ->helperText('Show only posts with this Sub-Division')
                            ->preload()
                            ->visible(fn($get) => $get('type') === PostType::POST_BY_SUBDIVISION),

                        Select::make('tag_id')
                            ->label('Filter by Tag')
                            ->options(fn() => Tag::pluck('name', 'id')->toArray())
                            ->searchable()
                            ->placeholder('Select a specific tag')
                            ->helperText('Show only posts with this tag')
                            ->preload()
                            ->visible(fn($get) => $get('type') === PostType::POST_BY_TAG),

                        Select::make('category_id')
                            ->label('Filter by Category')
                            ->options(fn() => Category::pluck('name', 'id')->toArray())
                            ->searchable()
                            ->multiple()
                            ->placeholder('Select a category')
                            ->helperText('Show only posts from this category')
                            ->preload()
                            ->visible(fn($get) => $get('type') === PostType::POST_BY_CATEGORY),

                        // Select::make('category_id')
                        //     ->label('Filter by Category')
                        //     ->options(fn() => Category::doesntHave('children')->pluck('name', 'id')->toArray())
                        //     ->searchable()
                        //     ->multiple()
                        //     ->placeholder('Select a category')
                        //     ->helperText('Show only posts from this category')
                        //     ->preload()
                        //     ->visible(fn($get) => $get('type') === PostType::POST_BY_CATEGORY),
                    ]),
            ])
            ->compact();
    }
}
