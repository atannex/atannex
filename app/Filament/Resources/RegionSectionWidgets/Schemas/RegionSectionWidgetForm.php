<?php

namespace App\Filament\Resources\RegionSectionWidgets\Schemas;

use App\Enums\Flag;
use App\Filament\Traits\ContentFiltering;
use App\Filament\Traits\EngagementWeightsSection;
use App\Filament\Traits\TabBasicInformation;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid as SchemaGrid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RegionSectionWidgetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            SchemaGrid::make(3)
                ->schema([
                    self::buildRightColumn(),
                    self::buildLeftColumn(),
                ])
                ->columnSpanFull(),
        ]);
    }

    /**
     * Build the left column containing configuration sections
     */
    protected static function buildLeftColumn(): Group
    {
        return Group::make()
            ->schema([
                self::pageAndSectionConfiguration(),
                self::displayOptionsConfiguration(),
            ])
            ->columnSpan(['lg' => 1]);
    }

    /**
     * Build the right column containing tab configurations
     */
    protected static function buildRightColumn(): Group
    {
        return Group::make()
            ->schema([
                self::sectionTabsConfiguration(),
                self::widgetTabsConfiguration(),
            ])
            ->columnSpan(['lg' => 2]);
    }

    /**
     * Page & Section Configuration Section
     * Configure where the widget will be displayed
     */
    protected static function pageAndSectionConfiguration(): Section
    {
        return Section::make('Page & Section Configuration')
            ->description('Select the page and section where this widget will be displayed')
            ->icon('heroicon-o-document-text')
            ->schema([
                self::regionField(),
                self::sectionField(),
                self::widgetField(),
            ])
            ->collapsible()
            ->collapsed()
            ->persistCollapsed();
    }

    /**
     * Display Options Configuration Section
     * Control widget visibility and behavior
     */
    protected static function displayOptionsConfiguration(): Section
    {
        return Section::make('Display Options')
            ->description('Control how and when this widget appears')
            ->icon('heroicon-o-cog-6-tooth')
            ->schema([
                self::positionField(),
                self::statusField(),
                self::metadataField(),
            ])
            ->collapsible()
            ->collapsed()
            ->persistCollapsed();
    }

    /**
     * Section Tabs Configuration
     * Create multiple tabs with different content filters
     */
    protected static function sectionTabsConfiguration(): Section
    {
        return Section::make('Section Tabs Configuration')
            ->description('Create and configure multiple tabs for dynamic content display')
            ->icon('heroicon-o-folder-open')
            ->schema([
                self::tabRepeater('config.section_tab', 'section'),
            ])
            ->collapsible()
            ->collapsed()
            ->persistCollapsed();
    }

    /**
     * Widget Tabs Configuration
     * Configuration specific to the selected widget
     */
    protected static function widgetTabsConfiguration(): Section
    {
        return Section::make('Widget Tabs Configuration')
            ->description('Configuration for the selected widget')
            ->icon('heroicon-o-wrench-screwdriver')
            ->schema([
                self::tabRepeater('config.widget_tab', 'widget'),
            ])
            ->visible(fn ($get) => filled($get('widget_id')))
            ->collapsible()
            ->collapsed()
            ->persistCollapsed();
    }

    /**
     * Region Selection Field
     */
    protected static function regionField(): Select
    {
        return Select::make('region_id')
            ->relationship('region', 'name')
            ->label('Target Region')
            ->required()
            ->searchable()
            ->preload()
            ->helperText('Choose the region where this widget will appear');
    }

    /**
     * Section Selection Field
     */
    protected static function sectionField(): Select
    {
        return Select::make('section_id')
            ->relationship('section', 'name')
            ->label('Section Location')
            ->required()
            ->searchable()
            ->preload()
            ->getOptionLabelFromRecordUsing(fn ($record) => sprintf(
                '#%s: %s',
                $record->slug,
                $record->name
            ))
            ->helperText('Select the specific section within the page');
    }

    /**
     * Widget Selection Field
     */
    protected static function widgetField(): Select
    {
        return Select::make('widget_id')
            ->relationship('widget', 'name')
            ->label('Widget')
            ->searchable()
            ->preload()
            ->reactive()
            ->afterStateUpdated(fn ($state, callable $set) => $set('selected_widget', $state))
            ->helperText('Select the widget to load its specific configuration');
    }

    /**
     * Position/Order Field
     */
    protected static function positionField(): TextInput
    {
        return TextInput::make('position')
            ->label('Display Order')
            ->numeric()
            ->default(0)
            ->required()
            ->helperText('Lower numbers appear first')
            ->placeholder('0');
    }

    /**
     * Status Flag Field
     */
    protected static function statusField(): Select
    {
        return Select::make('flag')
            ->label('Status')
            ->options(Flag::labels())
            ->searchable()
            ->preload()
            ->required()
            ->default(Flag::PENDING);
    }

    /**
     * Metadata Field
     */
    protected static function metadataField(): Textarea
    {
        return Textarea::make('metadata')
            ->label('Additional Metadata')
            ->rows(3)
            ->placeholder('Optional JSON or key-value metadata')
            ->default(null);
    }

    /**
     * Reusable Tab Repeater Component
     *
     * @param  string  $name  The field name
     * @param  string  $type  The tab type (section or widget)
     */
    protected static function tabRepeater(string $name, string $type): Repeater
    {
        $helperTexts = [
            'section' => 'Create multiple tabs with different content filters. Drag to reorder.',
            'widget' => 'This configuration depends on the selected widget.',
        ];

        return self::collapsibleRepeater(
            Repeater::make($name)
                ->schema([
                    TabBasicInformation::make(),
                    ContentFiltering::make(),
                    EngagementWeightsSection::make(),
                ])
                ->columns(1)
                ->itemLabel(fn (array $state): ?string => $state['title'] ?? 'Untitled Tab')
                ->addActionLabel('Add New Tab')
                ->reorderable()
                ->cloneable()
                ->minItems(1)
                ->maxItems(6)
                ->defaultItems(1)
                ->helperText($helperTexts[$type] ?? '')
        );
    }

    /**
     * Helper to apply consistent collapsible behavior
     */
    protected static function collapsibleRepeater(Repeater $repeater): Repeater
    {
        return $repeater
            ->collapsible()
            ->collapsed()
            ->persistCollapsed();
    }
}
