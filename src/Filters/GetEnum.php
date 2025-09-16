<?php

namespace Atannex\Filters;

trait GetEnum
{
    /**
     * Stores metadata for all enum cases.
     * Format: [enumValue => ['label' => ..., 'color' => ..., 'icon' => ..., ...]]
     */
    protected static $metadata = [];

    /**
     * Retrieve the full metadata array for the current enum value.
     */
    public function getMetadata()
    {
        return static::$metadata[$this->value];
    }

    /**
     * Retrieve a specific metadata field for the current enum value.
     *
     * @param string $field The metadata key to retrieve
     */
    public function getMetaField($field)
    {
        return $this->getMetadata()[$field];
    }

    /**
     * Get the 'label' of the current enum value.
     */
    public function getLabel()
    {
        return $this->getMetaField('label');
    }

    /**
     * Get the 'description' of the current enum value.
     */
    public function getDescriptions()
    {
        return $this->getMetaField('description');
    }

    /**
     * Get the 'color' associated with the current enum value.
     */
    public function getColor()
    {
        return $this->getMetaField('color');
    }

    /**
     * Get the 'icon' associated with the current enum value.
     */
    public function getIcon()
    {
        return $this->getMetaField('icon');
    }

    /**
     * Return all metadata for all enum values.
     */
    public static function getOptions()
    {
        return static::$metadata;
    }

    /**
     * Return all enum values (keys of the metadata array).
     */
    public static function values()
    {
        return array_keys(static::$metadata);
    }

    /**
     * Return an array mapping enum values to their labels.
     */
    public static function labels()
    {
        return array_map(
            function ($meta) { return $meta['label']; },
            static::$metadata
        );
    }

    /**
     * Set metadata for all enum cases at once.
     *
     * @param array $metadata Array of metadata keyed by enum values
     */
    public static function setMetadata($metadata)
    {
        static::$metadata = $metadata;
    }

    /**
     * Add or update metadata for a specific enum value.
     *
     * @param string $value Enum value
     * @param array $metadata Metadata to assign
     */
    public static function addMetadata($value, $metadata)
    {
        static::$metadata[$value] = $metadata;
    }
}
