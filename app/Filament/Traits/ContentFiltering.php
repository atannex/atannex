<?php

namespace App\Filament\Traits;

use App\Enums\Entity;
use App\Models\Regions\Category;
use App\Models\Regions\Region;
use App\Models\Tags\Tag;
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
                        ->options(Entity::asArray())
                        ->live()
                        ->searchable()
                        ->preload()
                        ->placeholder('Choose content type')
                        ->helperText('Determines how posts are fetched'),

                    Select::make('region_id')
                        ->label('Filter by Region')
                        ->options(fn () => self::region())
                        ->searchable()
                        ->multiple()
                        ->placeholder('Select Region')
                        ->helperText('Show only posts with this Region')
                        ->preload()
                        ->visible(fn (Get $get) => $get('type') === Entity::POSTS_BY_REGION),

                    Select::make('tag_id')
                        ->label('Filter by Tag')
                        ->options(fn () => self::tags())
                        ->searchable()
                        ->multiple()
                        ->placeholder('Select Tags')
                        ->helperText('Show only posts with this Tag')
                        ->preload()
                        ->visible(fn (Get $get) => $get('type') === Entity::POSTS_BY_TAG),

                    Select::make('category_id')
                        ->label('Filter by Category')
                        ->options(fn () => self::categories())
                        ->searchable()
                        ->multiple()
                        ->placeholder('Select Categories')
                        ->helperText('Show only posts from this Category')
                        ->preload()
                        ->visible(fn (Get $get) => $get('type') === Entity::POSTS_BY_CATEGORY),

                    Select::make('posts_with_id')
                        ->label('Select Entity')
                        ->options(fn (Get $get) => match ($get('type')) {
                            Entity::POSTS_BY_TAG => self::tags(),
                            Entity::REGIONS_WITH_POSTS => self::region(),
                            Entity::CATEGORIES_WITH_POSTS => self::categories(),
                            default => [],
                        })
                        ->searchable()
                        ->multiple()
                        ->placeholder('Select entities')
                        ->helperText('Show posts under selected entities')
                        ->preload()
                        ->visible(fn (Get $get) => in_array($get('type'), [
                            Entity::POSTS_BY_TAG,
                            Entity::REGIONS_WITH_POSTS,
                            Entity::CATEGORIES_WITH_POSTS,
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
