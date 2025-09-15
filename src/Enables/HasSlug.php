<?php

namespace Atannex\Enables;

use Spatie\Sluggable\HasSlug as Slug;
use Spatie\Sluggable\SlugOptions;

/**
 * Trait EnableSlug
 *
 * Provides functionality to automatically generate and manage URL-friendly slugs
 * for Eloquent models using the Spatie Sluggable package.
 */
trait HasSlug
{
    use Slug;

    /**
     * Get the source field for slug generation.
     *
     * @return string The name of the field to generate the slug from
     */
    protected function getSlugSource(): string
    {
        return $this->slugSource;
    }

    /**
     * Get the destination field for storing the generated slug.
     *
     * @return string The name of the field to store the slug
     */
    protected function getSlugDestination(): string
    {
        return $this->slugDestination ?? 'slug';
    }

    /**
     * Configure the slug generation options.
     *
     * @return SlugOptions The configured slug options
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom($this->getSlugSource())
            ->saveSlugsTo($this->getSlugDestination());
    }
}
