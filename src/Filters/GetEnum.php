<?php

namespace Atannex\Filters;

trait GetEnum
{
    /**
     * Stores metadata for all enum cases.
     * Format: [enumValue => ['label' => ..., 'color' => ..., 'icon' => ..., 'description' => ...]]
     */
    protected static $metadata = [];

    /**
     * Retrieve metadata for the current enum value or a specific field.
     *
     * @param string|null $field The metadata key to retrieve, or null for full metadata
     */
    public function getMetadata(?string $field = null)
    {
        $metadata = static::$metadata[$this->value];
        return $field !== null ? $metadata[$field] : $metadata;
    }

    /**
     * Get the 'label' of the current enum value.
     */
    public function getLabel(): string
    {
        return $this->getMetadata('label');
    }

    /**
     * Get the 'description' metadata of the current enum value.
     */
    public function getDescriptionMetadata(): string
    {
        return $this->getMetadata('description');
    }

    /**
     * Get the 'color' associated with the current enum value.
     */
    public function getColor(): string
    {
        return $this->getMetadata('color');
    }

    /**
     * Get the 'icon' associated with the current enum value.
     */
    public function getIcon(): string
    {
        return $this->getMetadata('icon');
    }

    /**
     * Return all metadata for all enum values.
     */
    public static function getOptions(): array
    {
        return static::$metadata;
    }

    /**
     * Return all enum values (keys of the metadata array).
     */
    public static function values(): array
    {
        return array_keys(static::$metadata);
    }

    /**
     * Return an array mapping enum values to their labels.
     */
    public static function labels(): array
    {
        // Return value => label pairs instead of just labels
        $pairs = [];
        foreach (static::$metadata as $value => $data) {
            $pairs[$value] = $data['label'];
        }

        return $pairs;
    }


    /**
     * Set metadata for all enum cases at once.
     *
     * @param array $metadata Array of metadata keyed by enum values
     */
    public static function setMetadata(array $metadata): void
    {
        static::$metadata = $metadata;
    }

    /**
     * Add or update metadata for a specific enum value.
     *
     * @param string $value Enum value
     * @param array $metadata Metadata to assign
     */
    public static function addMetadata(string $value, array $metadata): void
    {
        static::$metadata[$value] = $metadata;
    }
}
