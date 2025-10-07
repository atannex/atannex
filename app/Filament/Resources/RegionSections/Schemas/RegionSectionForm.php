<?php

namespace App\Filament\Resources\RegionSections\Schemas;

use App\Enums\Flag;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use App\Filament\Traits\ContentFiltering;
use App\Filament\Traits\TabBasicInformation;
use App\Filament\Traits\EngagementWeightsSection;
use Filament\Schemas\Components\Grid as SchemaGrid;

class RegionSectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            SchemaGrid::make(2)
                ->schema([
                    Group::make()
                        ->schema([
                            Section::make('Page & Section Configuration')
                                ->description('Select the page and section where this widget will be displayed')
                                ->icon('heroicon-o-document-text')
                                ->schema([
                                    SchemaGrid::make(1)
                                        ->schema([
                                            Select::make('region_id')
                                                ->relationship('region', 'name')
                                                ->label('Target Region')
                                                ->required()
                                                ->searchable()
                                                ->preload()
                                                ->helperText('Choose the region where this widget will appear'),

                                            Select::make('section_id')
                                                ->relationship('section', 'name')
                                                ->label('Section Location')
                                                ->required()
                                                ->searchable()
                                                ->preload()
                                                ->getOptionLabelFromRecordUsing(fn($record) => sprintf('#-%s: %s', $record->slug, $record->name))
                                                ->helperText('Select the specific section within the page'),
                                        ]),
                                ])
                                ->collapsible()
                                ->persistCollapsed(),

                            Section::make('Display Options')
                                ->description('Control how and when this widget appears')
                                ->icon('heroicon-o-cog-6-tooth')
                                ->schema([
                                    SchemaGrid::make(1)
                                        ->schema([
                                            TextInput::make('position')
                                                ->label('Display Order')
                                                ->numeric()
                                                ->default(0)
                                                ->required()
                                                ->helperText('Lower numbers appear first')
                                                ->placeholder('0'),

                                            Select::make('flag')
                                                ->options(Flag::labels())
                                                ->preload()
                                                ->searchable()
                                                ->required()
                                                ->default('pending'),
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
                                        ->schema([
                                            TabBasicInformation::make(),
                                            ContentFiltering::make(),
                                            EngagementWeightsSection::make(),
                                        ])
                                        ->columns(1)
                                        ->itemLabel(fn(array $state): ?string => $state['title'] ?? 'Untitled Tab')
                                        ->addActionLabel('Add New Tab')
                                        ->reorderable()
                                        ->collapsible()
                                        ->cloneable()
                                        ->minItems(1)
                                        ->maxItems(6)
                                        ->defaultItems(1)
                                        ->helperText('Create multiple tabs with different content filters. Drag to reorder.')
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
