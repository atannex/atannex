<?php

namespace App\Filament\Traits;

use Closure;
use InvalidArgumentException;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;

/**
 * Provides reusable methods for creating Filament table columns and form fields
 * for enum-based data and relationships, ensuring consistent styling and behavior.
 */
trait HasEnumColumnAndField
{
    /**
     * Creates a styled table column for an enum field.
     *
     * @param string $name The column name
     * @param class-string $enumClass The enum class
     * @param bool $withIcon Whether to display the icon
     * @return TextColumn
     */
    public static function makeEnumColumn(string $name, string $enumClass, bool $withIcon = true): TextColumn
    {
        return self::createEnumComponent('column', $name, $enumClass, $withIcon);
    }

    /**
     * Creates a form select field for an enum.
     *
     * @param string $name The field name
     * @param class-string $enumClass The enum class
     * @param mixed|null $default Optional default value
     * @return Select
     */
    public static function makeEnumField(string $name, string $enumClass, mixed $default = null): Select
    {
        return self::createEnumComponent('field', $name, $enumClass, null, $default);
    }

    /**
     * Creates a select field for a relationship.
     *
     * @param string $name The field name
     * @param string $relationship The relationship name
     * @param string $displayColumn The column to display in the select
     * @param bool $nullable Whether the field is nullable
     * @return Select
     */
    public static function makeRelationshipField(
        string $name,
        string $relationship,
        string $displayColumn,
        bool $nullable = true,
        ?Closure $queryCallback = null,
        ?Closure $getLabel = null,
        ?string $helperText = null
    ): Select {
        $field = Select::make($name)
            ->relationship($relationship, $displayColumn, $queryCallback)
            ->searchable()
            ->preload();

        if ($nullable) {
            $field->nullable();
        }

        if ($getLabel instanceof Closure) {
            $field->getOptionLabelFromRecordUsing($getLabel);
        }

        if ($helperText) {
            $field->helperText($helperText);
        }

        return $field;
    }

    /**
     * Internal helper to create enum-based table columns or form fields.
     *
     * @param 'column'|'field' $type
     * @param string $name
     * @param class-string $enumClass
     * @param bool|null $withIcon Optional, only for columns
     * @param mixed|null $default Optional, only for fields
     * @return TextColumn|Select
     */
    private static function createEnumComponent(
        string $type,
        string $name,
        string $enumClass,
        ?bool $withIcon = null,
        mixed $default = null
    ) {
        if ($type === 'column') {
            $column = TextColumn::make($name)
                ->badge()
                ->searchable()
                ->sortable()
                ->color(fn(string $state): string => $enumClass::fromValue($state)->getColor())
                ->formatStateUsing(fn(string $state): string => $enumClass::fromValue($state)->getLabel());

            if ($withIcon) {
                $column->icon(fn(string $state): string => $enumClass::fromValue($state)->getIcon());
            }

            return $column;
        }

        if ($type === 'field') {
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

        throw new InvalidArgumentException('Invalid enum component type: ' . $type);
    }
}
