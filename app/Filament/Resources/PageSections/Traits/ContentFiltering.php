<?php

declare(strict_types=1);

namespace App\Filament\Resources\PageSections\Traits;

use App\Enums\PostType;
use App\Models\Tags\Tag;
use App\Models\Pages\Category;
use App\Models\Regions\Region;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;

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
                        ->options(fn () => self::region())
                        ->searchable()
                        ->multiple()
                        ->placeholder('Select Region')
                        ->helperText('Show only posts with this Region')
                        ->preload()
                        ->visible(fn (Get $get) => $get('type') === PostType::POST_BY_REGION),

                    Select::make('tag_id')
                        ->label('Filter by Tag')
                        ->options(fn () => self::tags())
                        ->searchable()
                        ->multiple()
                        ->placeholder('Select Tags')
                        ->helperText('Show only posts with this Tag')
                        ->preload()
                        ->visible(fn (Get $get) => $get('type') === PostType::POST_BY_TAG),

                    Select::make('category_id')
                        ->label('Filter by Category')
                        ->options(fn () => self::categories())
                        ->searchable()
                        ->multiple()
                        ->placeholder('Select Categories')
                        ->helperText('Show only posts from this Category')
                        ->preload()
                        ->visible(fn (Get $get) => $get('type') === PostType::POST_BY_CATEGORY),

                    Select::make('posts_with_id')
                        ->label('Select Entity')
                        ->options(fn (Get $get) => match ($get('type')) {
                            PostType::GET_TAG_WITH_POSTS => self::tags(),
                            PostType::GET_REGION_WITH_POSTS => self::region(),
                            PostType::GET_CATEGORY_WITH_POSTS => self::categories(),
                            default => [],
                        })
                        ->searchable()
                        ->multiple()
                        ->placeholder('Select entities')
                        ->helperText('Show posts under selected entities')
                        ->preload()
                        ->visible(fn (Get $get) => in_array($get('type'), [
                            PostType::GET_TAG_WITH_POSTS,
                            PostType::GET_REGION_WITH_POSTS,
                            PostType::GET_CATEGORY_WITH_POSTS,
                        ])),
                ]),
            ])
            ->compact();
    }

    /** @return array<int, string> */
    private static function region(): array
    {
        return Region::pluck('name', 'id')->toArray();
    }

    /** @return array<int, string> */
    private static function tags(): array
    {
        return Tag::pluck('name', 'id')->toArray();
    }

    /** @return array<int, string> */
    private static function categories(): array
    {
        return Category::pluck('name', 'id')->toArray();
    }
}
