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
use App\Filament\Resources\PageSections\Traits\ContentFiltering;
use App\Filament\Resources\PageSections\Traits\DateRangeSection;
use App\Filament\Resources\PageSections\Traits\TabBasicInformation;
use App\Filament\Resources\PageSections\Traits\ScoringOptionsSection;
use App\Filament\Resources\PageSections\Traits\EngagementWeightsSection;
use App\Filament\Resources\PageSections\Traits\AdditionalSettingsSection;

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
                                            ->schema([
                                                TabBasicInformation::make(),
                                                ContentFiltering::make(),
                                                DateRangeSection::make(),
                                                EngagementWeightsSection::make(),
                                                ScoringOptionsSection::make(),
                                                AdditionalSettingsSection::make(),
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
                                    ]),
                            ])
                            ->collapsible()
                            ->persistCollapsed(),
                    ])
                    ->columnSpan(1),
            ]);
    }
}
