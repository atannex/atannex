<?php

namespace Atangageih\Filters;

trait GetEnum
{
    /**
     * Default metadata structure for unknown values.
     *
     * @var array<string, mixed>
     */
    protected static array $defaultMetadata = [
        'label' => null,
        'color' => 'gray',
        'icon' => 'heroicon-o-question-mark-circle',
    ];

    /**
     * Metadata storage for enum cases.
     *
     * @var array<string, array<string, mixed>>
     */
    protected static array $metadata = [];

    /**
     * Get metadata for the current enum case.
     *
     * @return array<string, mixed>
     */
    public function getMetadata(): array
    {
        return static::$metadata[$this->value] ?? static::getDefaultMetadata($this->value);
    }

    /**
     * Get a specific metadata field.
     *
     * @param string $field The metadata field to retrieve
     * @param mixed $default Default value if field is not found
     * @return mixed
     */
    public function getMetaField(string $field, mixed $default = null): mixed
    {
        $metadata = $this->getMetadata();
        return $metadata[$field] ?? $default;
    }

    /**
     * Get the label for the current enum case.
     *
     * @return string
     */
    public function getLabel(): string
    {
        return (string) $this->getMetaField('label', $this->value);
    }

    public function getDescriptions(): string
    {
        return (string) $this->getMetaField('description', $this->value);
    }

    /**
     * Get the color for the current enum case.
     *
     * @return string
     */
    public function getColor(): string
    {
        return (string) $this->getMetaField('color', static::$defaultMetadata['color']);
    }

    /**
     * Get the icon for the current enum case.
     *
     * @return string
     */
    public function getIcon(): string
    {
        return (string) $this->getMetaField('icon', static::$defaultMetadata['icon']);
    }

    /**
     * Get all metadata for all enum cases.
     *
     * @return array<string, array<string, mixed>>
     */
    public static function getOptions(): array
    {
        return static::$metadata;
    }

    /**
     * Get all enum values.
     *
     * @return string[]
     */
    public static function values(): array
    {
        return array_keys(static::$metadata);
    }

    /**
     * Get key => label array for all enum cases.
     *
     * @return array<string, string>
     */
    public static function labels(): array
    {
        return array_map(
            fn($meta) => (string) ($meta['label'] ?? ''),
            static::$metadata
        );
    }

    /**
     * Provide default metadata structure for unknown values.
     *
     * @param string $value The enum value
     * @return array<string, mixed>
     */
    protected static function getDefaultMetadata(string $value): array
    {
        return array_merge(static::$defaultMetadata, ['label' => $value]);
    }

    /**
     * Set custom default metadata for the enum.
     *
     * @param array<string, mixed> $metadata
     * @return void
     */
    public static function setDefaultMetadata(array $metadata): void
    {
        static::$defaultMetadata = array_merge(static::$defaultMetadata, $metadata);
    }

    /**
     * Set metadata for all enum cases.
     *
     * @param array<string, array<string, mixed>> $metadata
     * @return void
     */
    public static function setMetadata(array $metadata): void
    {
        static::$metadata = $metadata;
    }

    /**
     * Add or update metadata for a specific enum value.
     *
     * @param string $value The enum value
     * @param array<string, mixed> $metadata
     * @return void
     */
    public static function addMetadata(string $value, array $metadata): void
    {
        static::$metadata[$value] = array_merge(
            static::getDefaultMetadata($value),
            $metadata
        );
    }
}
