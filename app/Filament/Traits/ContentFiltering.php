<?php

namespace App\Filament\Traits;

use App\Enums\Entity;
use App\Models\Regions\Category;
use App\Models\Regions\Region;
use App\Models\Tags\Tag;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;

class ContentFiltering
{
    /**
     * Builds the Filament form Section containing controls for content filtering.
     *
     * The section includes Select controls for choosing content type and for filtering by
     * region, tag, category, or specific entities. Visibility and options of the filters
     * are driven by the selected content type.
     *
     * @return \Filament\Forms\Components\Section The constructed Section configured for content filtering.
     */
    public static function make(): Section
    {
        return Section::make('Content Filtering')
            ->schema([
                Grid::make(1)->schema([
                    Select::make('type')
                        ->label('Content Type')
                        ->options(Entity::asSelectArray())
                        ->live()
                        ->searchable()
                        ->preload()
                        ->placeholder('Choose content type')
                        ->helperText('Determines how posts are fetched'),

                    Select::make('region_id')
                        ->label('Filter by Region')
                        ->options(fn() => self::regions())
                        ->searchable()
                        ->multiple()
                        ->placeholder('Select Region')
                        ->helperText('Show only posts with this Region')
                        ->preload()
                        ->visible(fn(Get $get) => $get('type') === Entity::POSTS_BY_REGION),

                    Select::make('tag_id')
                        ->label('Filter by Tag')
                        ->options(fn() => self::tags())
                        ->searchable()
                        ->multiple()
                        ->placeholder('Select Tags')
                        ->helperText('Show only posts with this Tag')
                        ->preload()
                        ->visible(fn(Get $get) => $get('type') === Entity::POSTS_BY_TAG),

                    Select::make('category_id')
                        ->label('Filter by Category')
                        ->options(fn() => self::categories())
                        ->searchable()
                        ->multiple()
                        ->placeholder('Select Categories')
                        ->helperText('Show only posts from this Category')
                        ->preload()
                        ->visible(fn(Get $get) => $get('type') === Entity::POSTS_BY_CATEGORY),

                    Select::make('author_id')
                        ->label('Filter by Author')
                        ->options(fn() => self::authors())
                        ->searchable()
                        ->multiple()
                        ->placeholder('Select Users')
                        ->helperText('Show only posts by this author')
                        ->preload()
                        ->visible(fn(Get $get) => $get('type') === Entity::POSTS_BY_AUTHOR),

                    Select::make('posts_with_id')
                        ->label('Select Entity')
                        ->options(fn(Get $get) => self::entityOptions($get('type')))
                        ->searchable()
                        ->multiple()
                        ->placeholder('Select entities')
                        ->helperText('Show posts under selected entities')
                        ->preload()
                        ->visible(fn(Get $get) => in_array($get('type'), [
                            Entity::POSTS_BY_TAG,
                            Entity::CATEGORIES_WITH_POSTS,
                            Entity::REGIONS_WITH_POSTS,
                            Entity::POSTS_BY_AUTHOR,
                        ])),

                    Select::make('timeframe')
                        ->label('Timeframe')
                        ->options([
                            'week'  => 'This Week',
                            'month' => 'This Month',
                        ])
                        ->searchable()
                        ->placeholder('Select timeframe')
                        ->helperText('Apply timeframe for trending or popular posts')
                        ->visible(fn(Get $get) => in_array($get('type'), [
                            Entity::POPULAR_THIS_WEEK,
                            Entity::POPULAR_THIS_MONTH,
                            Entity::TRENDING_THIS_WEEK,
                            Entity::TRENDING_THIS_MONTH,
                        ])),
                ]),
            ])
            ->compact();
    }

    /**
     * Map region IDs to their names.
     *
     * @return array<int, string> An associative array where keys are region IDs and values are region names.
     */
    private static function regions(): array
    {
        return Region::pluck('name', 'id')->toArray();
    }

    /** @return array<int, string> */
    private static function tags(): array
    {
        return Tag::pluck('name', 'id')->toArray();
    }

    /**
     * An associative array mapping category IDs to category names.
     *
     * @return array<int,string> Mapping of category `id` => `name`.
     */
    private static function categories(): array
    {
        return Category::pluck('name', 'id')->toArray();
    }

    /**
     * Get available author options keyed by user id.
     *
     * @return array<int, string> Mapping of user id to user name.
     */
    private static function authors(): array
    {
        return User::pluck('name', 'id')->toArray();
    }

    /**
     * Selects the option list appropriate for the given content entity type.
     *
     * Returns an associative array of id => display name for the entity type:
     * tags for POSTS_BY_TAG, categories for CATEGORIES_WITH_POSTS, regions for REGIONS_WITH_POSTS,
     * authors for POSTS_BY_AUTHOR, or an empty array for other types.
     *
     * @param string $type The entity type identifier.
     * @return array<int|string,string> Associative array mapping option id to display name.
     */
    private static function entityOptions(string $type): array
    {
        return match ($type) {
            Entity::POSTS_BY_TAG           => self::tags(),
            Entity::CATEGORIES_WITH_POSTS  => self::categories(),
            Entity::REGIONS_WITH_POSTS     => self::regions(),
            Entity::POSTS_BY_AUTHOR        => self::authors(),
            default                         => [],
        };
    }
}
