<?php

namespace App\Filament\Resources\PageSections\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid as SchemaGrid;
use App\Filament\Resources\PageSections\Traits\ContentFiltering;
use App\Filament\Resources\PageSections\Traits\DateRangeSection;
use App\Filament\Resources\PageSections\Traits\TabBasicInformation;
use App\Filament\Resources\PageSections\Traits\ScoringOptionsSection;
use App\Filament\Resources\PageSections\Traits\EngagementWeightsSection;
use App\Filament\Resources\PageSections\Traits\AdditionalSettingsSection;

class PageSectionForm
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
