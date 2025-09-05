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
     */
    public static function make(): Section
    {
        return Section::make('Content Filtering')
            ->schema([
                Grid::make(1)->schema([
                    Select::make('type')
                        ->label('Content Type')
                        ->options(PostType::labels())
                        ->live()
                        ->searchable()
                        ->preload()
                        ->placeholder('Choose content type')
                        ->helperText('Determines how posts are fetched')
                        ->required(),

                    Select::make('region_id')
                        ->label('Filter by Region')
                        ->options(fn() => self::region())
                        ->searchable()
                        ->multiple()
                        ->placeholder('Select Region')
                        ->helperText('Show only posts with this Region')
                        ->preload()
                        ->visible(fn($get) => $get('type') === PostType::POST_BY_REGION),

                    // Select::make('territory')
                    //     ->label('Filter by Post Territory')
                    //     ->options(Territories::labels())
                    //     ->searchable()
                    //     ->placeholder('Select Post Territory')
                    //     ->helperText('Show only posts with this Territory')
                    //     ->preload()
                    //     ->visible(
                    //         fn($get) =>
                    //         $get('type') === PostType::GET_REGION_WITH_POSTS ||
                    //             $get('type') === PostType::POST_BY_REGION
                    //     ),

                    Select::make('tag_id')
                        ->label('Filter by Tag')
                        ->options(fn() => self::tags())
                        ->searchable()
                        ->multiple()
                        ->placeholder('Select Tags')
                        ->helperText('Show only posts with this Tag')
                        ->preload()
                        ->visible(fn($get) => $get('type') === PostType::POST_BY_TAG),

                    Select::make('category_id')
                        ->label('Filter by Category')
                        ->options(fn() => self::categories())
                        ->searchable()
                        ->multiple()
                        ->placeholder('Select Categories')
                        ->helperText('Show only posts from this Category')
                        ->preload()
                        ->visible(fn($get) => $get('type') === PostType::POST_BY_CATEGORY),

                    // Get entities with all posts
                    Select::make('tag_with_post_id')
                        ->label('Get Tag and All Corresponding Posts')
                        ->options(fn() => self::tags())
                        ->searchable()
                        ->multiple()
                        ->placeholder('Select Tags')
                        ->helperText('Show posts under selected Tags')
                        ->preload()
                        ->visible(fn($get) => $get('type') === PostType::GET_TAG_WITH_POSTS),

                    Select::make('region_with_post_id')
                        ->label('Get Region and All Corresponding Posts')
                        ->options(fn() => self::region())
                        ->searchable()
                        ->multiple()
                        ->placeholder('Select Region')
                        ->helperText('Show posts under selected Region')
                        ->preload()
                        ->visible(fn($get) => $get('type') === PostType::GET_REGION_WITH_POSTS),

                    Select::make('category_with_post_id')
                        ->label('Get Category and All Corresponding Posts')
                        ->options(fn() => self::categories())
                        ->searchable()
                        ->multiple()
                        ->placeholder('Select Categories')
                        ->helperText('Show posts under selected Categories')
                        ->preload()
                        ->visible(fn($get) => $get('type') === PostType::GET_CATEGORY_WITH_POSTS),
                ]),
            ])
            ->compact();
    }

    /** @return array<int,string> */
    private static function region(): array
    {
        return Region::pluck('name', 'id')->toArray();
    }

    /** @return array<int,string> */
    private static function tags(): array
    {
        return Tag::pluck('name', 'id')->toArray();
    }

    /** @return array<int,string> */
    private static function categories(): array
    {
        return Category::pluck('name', 'id')->toArray();
    }
}
