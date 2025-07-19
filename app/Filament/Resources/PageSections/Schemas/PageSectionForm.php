<?php

namespace App\Filament\Resources\PageSections\Schemas;

use App\Enums\PostType;
use App\Enums\Filtering;
use App\Models\Tags\Tag;
use Filament\Schemas\Schema;
use App\Models\Pages\Category;
use App\Models\Regions\Region;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class PageSectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(2)
                ->schema([
                    Group::make()
                        ->schema([
                            Section::make('Page & Section Configuration')
                                ->description('Select the page and section where this widget will be displayed')
                                ->icon('heroicon-o-document-text')
                                ->schema([
                                    Grid::make(1)
                                        ->schema([
                                            Select::make('page_id')
                                                ->relationship('page', 'title')
                                                ->label('Target Page')
                                                ->required()
                                                ->searchable()
                                                ->preload()
                                                ->helperText('Choose the page where this widget will appear'),

                                            Select::make('section_id')
                                                ->relationship('section', 'name')
                                                ->label('Section Location')
                                                ->required()
                                                ->searchable()
                                                ->preload()
                                                ->getOptionLabelFromRecordUsing(fn($record) => "#-{$record->slug}: {$record->name}")
                                                ->helperText('Select the specific section within the page'),
                                        ]),
                                ])
                                ->collapsible()
                                ->persistCollapsed(),

                            Section::make('Display Options')
                                ->description('Control how and when this widget appears')
                                ->icon('heroicon-o-cog-6-tooth')
                                ->schema([
                                    Grid::make(1)
                                        ->schema([
                                            TextInput::make('position')
                                                ->label('Display Order')
                                                ->numeric()
                                                ->default(0)
                                                ->required()
                                                ->helperText('Lower numbers appear first')
                                                ->placeholder('0'),

                                            Toggle::make('is_active')
                                                ->label('Enable Widget')
                                                ->default(true)
                                                ->helperText('Toggle to show/hide this widget')
                                                ->inline(false),
                                        ]),
                                ])
                                ->collapsible()
                                ->persistCollapsed(),
                        ])
                        ->columnSpan(1),

                    Group::make()
                        ->schema([
                            Section::make('Widget Tabs Configuration')
                                ->description('Create and configure multiple tabs for dynamic content display')
                                ->icon('heroicon-o-folder-open')
                                ->schema([
                                    Repeater::make('config.tabs')
                                        ->label('Content Tabs')
                                        ->schema([
                                            Group::make()
                                                ->schema([
                                                    Section::make('Tab Basic Information')
                                                        ->schema([
                                                            Grid::make(2)
                                                                ->schema([
                                                                    TextInput::make('title')
                                                                        ->label('Tab Title')
                                                                        ->placeholder('e.g., Breaking News, Latest Updates')
                                                                        ->helperText('Display name for this tab')
                                                                        ->maxLength(50),

                                                                    TextInput::make('limit')
                                                                        ->label('Content Limit')
                                                                        ->numeric()
                                                                        ->default(5)
                                                                        ->minValue(1)
                                                                        ->maxValue(50)
                                                                        ->suffix('posts')
                                                                        ->helperText('Maximum number of posts to display'),
                                                                ]),
                                                        ])
                                                        ->compact(),

                                                     Section::make('Content Filtering')
                                                    ->schema([
                                                        Grid::make(1)
                                                            ->schema([
                                                                Select::make('type')
                                                                    ->label('Content Type')
                                                                    ->options(PostType::labels())
                                                                    ->live()
                                                                    ->placeholder('Choose content type')
                                                                    ->helperText('Determines how posts are fetched'),

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


                                                                // Region Content Filter
                                                                Select::make('region_post_filter')
                                                                    ->label('Region Content Filter')
                                                                    ->options(Filtering::labels())
                                                                    ->searchable()
                                                                    ->preload()
                                                                    ->placeholder('Choose region content type')
                                                                    ->helperText('Choose the type of content to display for the selected region')
                                                                    ->visible(fn($get) => $get('type') === PostType::POST_BY_FONDOM),

                                                                // Tag Filter
                                                                Select::make('tag_id')
                                                                    ->label('Filter by Tag')
                                                                    ->options(fn() => Tag::pluck('name', 'id')->toArray())
                                                                    ->searchable()
                                                                    ->placeholder('Select a specific tag')
                                                                    ->helperText('Show only posts with this tag')
                                                                    ->preload()
                                                                    ->visible(fn($get) => $get('type') === PostType::POST_BY_TAG),

                                                                // Category Filter
                                                                Select::make('category_id')
                                                                    ->label('Filter by Category')
                                                                    ->options(fn() => Category::doesntHave('children')->pluck('name', 'id')->toArray())
                                                                    ->searchable()
                                                                    ->multiple()
                                                                    ->placeholder('Select a category')
                                                                    ->helperText('Show only posts from this category')
                                                                    ->preload()
                                                                    ->visible(fn($get) => $get('type') === PostType::POST_BY_CATEGORY),
                                                            ]),
                                                    ])
                                                    ->compact(),


                                                    Section::make('Additional Settings')
                                                        ->schema([
                                                            Grid::make(1)
                                                                ->schema([
                                                                    Textarea::make('description')
                                                                        ->label('Internal Notes')
                                                                        ->placeholder('Add internal notes about this tab (not visible to users)')
                                                                        ->rows(2)
                                                                        ->helperText('For administrative purposes only')
                                                                        ->columnSpanFull(),
                                                                ]),
                                                        ])
                                                        ->compact()
                                                        ->collapsible()
                                                        ->collapsed(),
                                                ])
                                                ->columnSpanFull(),
                                        ])
                                        ->columns(1)
                                        ->itemLabel(fn(array $state): ?string => $state['title'] ?? 'Untitled Tab')
                                        ->addActionLabel('Add New Tab')
                                        ->reorderable()
                                        ->collapsible()
                                        ->collapsed()
                                        ->cloneable()
                                        ->minItems(1)
                                        ->maxItems(6)
                                        ->defaultItems(1)
                                        ->helperText('Create multiple tabs with different content filters. Drag to reorder.')
                                        ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                                            if (empty($data['tabs'])) {
                                                $data['tabs'] = [
                                                    [
                                                        'title' => 'Default Tab',
                                                        'limit' => '5',
                                                        'type' => PostType::BREAKING_POST,
                                                    ]
                                                ];
                                            }
                                            return $data;
                                        }),
                                ])
                                ->collapsible()
                                ->persistCollapsed(),
                        ])
                        ->columnSpan(1),
                ])
                ->columnSpanFull(),
        ]);
    }
}
