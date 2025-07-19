<?php

namespace App\Filament\Traits;

use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;

/**
 * Provides reusable methods for creating Filament table columns and form fields
 * for enum-based data with consistent formatting and styling.
 */
trait HasEnumColumnAndField
{
    /**
     * Creates a styled table column for an enum field without a label.
     *
     * @param string $name The name of the column
     * @param string $enumClass The fully qualified class name of the enum
     * @param bool $withIcon Whether to display the icon
     * @return TextColumn The configured table column
     */
    public static function makeEnumColumn(
        string $name,
        string $enumClass,
        bool $withIcon = true
    ): TextColumn {
        $column = TextColumn::make($name)
            ->badge()
            ->searchable()
            ->color(fn(string $state): string => $enumClass::fromValue($state)->getColor())
            ->formatStateUsing(fn(string $state): string => $enumClass::fromValue($state)->getLabel())
            ->sortable();

        if ($withIcon) {
            $column->icon(fn(string $state): string => $enumClass::fromValue($state)->getIcon());
        }

        return $column;
    }

    /**
     * Creates a form select field for an enum without a label.
     *
     * @param string $name The name of the form field
     * @param string $enumClass The fully qualified class name of the enum
     * @param mixed|null $default The default value for the select field (optional)
     * @return Select The configured form select field
     */
    public static function makeEnumField(
        string $name,
        string $enumClass,
        mixed $default = null
    ): Select {
        $field = Select::make($name)
            ->options($enumClass::labels())
            ->required()
            ->searchable()
            ->preload()
            ->native(false);

        if ($default !== null) {
            $field->default($default);
        }

        return $field;
    }

    public static function makeRelationshipField(
        string $name,
        string $relationship,
        string $displayColumn,
    ): Select {
        return Select::make($name)
            ->relationship($relationship, $displayColumn)
            ->searchable()
            ->preload()
            ->nullable()
            ->searchable();
    }
}
