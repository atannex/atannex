<?php

namespace Atannex\Enables;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * Trait EnableSlug
 *
 * Provides automatic slug generation for Eloquent models using Spatie Sluggable.
 */
trait EnableSlug
{
    use HasSlug;

    /**
     * Get the source field(s) for slug generation.
     *
     * @return string|array
     */
    protected function getSlugSource(): string|array
    {
        return property_exists($this, 'slugSource') ? $this->slugSource : 'title';
    }

    /**
     * Get the destination field for storing the generated slug.
     *
     * @return string
     */
    protected function getSlugDestination(): string
    {
        return property_exists($this, 'slugDestination') ? $this->slugDestination : 'slug';
    }

    /**
     * Configure the slug generation options.
     *
     * @return SlugOptions
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom($this->getSlugSource())
            ->saveSlugsTo($this->getSlugDestination())
            ->usingSeparator('-')
            ->doNotGenerateSlugsOnUpdate();
    }
}
