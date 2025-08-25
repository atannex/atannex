<?php

namespace App\Filament\Resources\WidgetSections\Schemas;

use App\Enums\PostType;
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

class WidgetSectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->schema([
                        Section::make('Widget Assignment')
                            ->description('Configure which widget to display in the selected section')
                            ->icon('heroicon-o-puzzle-piece')
                            ->schema([
                                Grid::make(1)
                                    ->schema([
                                        Select::make('section_id')
                                            ->label('Section')
                                            ->relationship('section', 'name')
                                            ->required()
                                            ->searchable()
                                            ->preload()
                                            ->getOptionLabelFromRecordUsing(fn($record) => sprintf('#%s: %s', $record->slug, $record->name))
                                            ->placeholder('Select a section')
                                            ->helperText('Choose the section where this widget will be displayed'),

                                        Select::make('widget_id')
                                            ->label('Widget')
                                            ->relationship('widget', 'name')
                                            ->required()
                                            ->searchable()
                                            ->preload()
                                            ->getOptionLabelFromRecordUsing(fn($record) => sprintf('#%s: %s', $record->slug, $record->name))
                                            ->placeholder('Select a widget')
                                            ->helperText('Choose the widget to display in this section'),
                                    ]),
                            ]),

                        Section::make('Widget Configuration')
                            ->description('Additional settings and positioning for the widget')
                            ->icon('heroicon-o-cog-6-tooth')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('position')
                                            ->label('Display Position')
                                            ->required()
                                            ->numeric()
                                            ->default(0)
                                            ->minValue(0)
                                            ->step(1)
                                            ->placeholder('0')
                                            ->helperText('Lower numbers appear first (0 = top position)'),

                                        Toggle::make('is_active')
                                            ->label('Active Status')
                                            ->helperText('Enable to display this widget')
                                            ->default(true)
                                            ->inline(false),
                                    ]),
                            ]),
                    ])
                    ->columnSpan(1),

                Group::make()
                    ->schema([
                        Section::make('Widget Tabs Configuration')
                            ->description('Create and configure multiple tabs for dynamic content display')
                            ->icon('heroicon-o-folder-open')
                            ->schema([
                                Grid::make(1)
                                    ->schema([
                                        Repeater::make('config.tabs')
                                            ->label('Content Tabs')
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
                                                                    ->helperText('For administrative purposes only'),
                                                            ]),
                                                    ])
                                                    ->compact()
                                                    ->collapsible()
                                                    ->collapsed(),
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
                                            ->helperText('Create multiple tabs with different content filters. Drag to reorder.'),
                                    ]),
                            ])
                            ->collapsible()
                            ->persistCollapsed(),
                    ])
                    ->columnSpan(1),
            ]);
    }
}
