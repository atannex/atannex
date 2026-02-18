<?php

namespace App\Filament\Resources\RegionSectionWidgets\Schemas;

use App\Enums\Flag;
use App\Filament\Traits\ContentFiltering;
use App\Filament\Traits\TabBasicInformation;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
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
     * Builds the "Display Options" form section for configuring a widget's display order and flag.
     *
     * @return \Filament\Forms\Components\Section A Section containing the display order and flag fields.
     */
    protected static function displayOptionsConfiguration(): Section
    {
        return Section::make('Display Options')
            ->description('Control how and when this widget appears')
            ->icon('heroicon-o-cog-6-tooth')
            ->schema([
                self::positionField(),
                self::statusField(),
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
     * Builds the form section for widget-specific tab configuration.
     *
     * The section contains a repeater for widget tabs and is visible only when a widget is selected (`widget_id` is filled).
     *
     * @return \Filament\Forms\Components\Section The Section configured for widget-specific tab settings.
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
     * Creates a Select field for choosing a section within the page.
     *
     * The field presents sections by name with option labels formatted as `#<slug>: <name>`,
     * is required, searchable, and preloaded, and includes helper text explaining its purpose.
     *
     * @return \Filament\Forms\Components\Select The configured select field for `section_id`.
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
     * Builds the "Widget" select used to choose a widget for the region section.
     *
     * The field is searchable, preloaded, and reactive; changing its value updates the form state key `selected_widget`.
     *
     * @return Select The configured Select component for selecting a widget by name.
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
     * Create the Select field for selecting the record's status flag.
     *
     * The field's options are populated from Flag::asSelectArray(), is searchable, preloaded,
     * required, and defaults to Flag::DRAFT.
     *
     * @return Select The Select field configured for the record's status flag.
     */
    protected static function statusField(): Select
    {
        return Select::make('flag')
            ->label('Flag')
            ->options(Flag::asSelectArray())
            ->searchable()
            ->preload()
            ->required()
            ->default(Flag::DRAFT);
    }

    /**
     * Create a collapsible repeater for configuring tabs of a specific type.
     *
     * The repeater contains basic tab information and content-filtering blocks, applies a single-column layout,
     * and enforces item behavior (reorderable, cloneable) and limits (minimum 1, maximum 6, default 1). The provided
     * `$type` selects context-specific helper text.
     *
     * @param  string  $name  The field name.
     * @param  string  $type  The tab type; expected values are `'section'` or `'widget'`, which determine helper text.
     * @return Repeater The configured Repeater instance.
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
