<?php

namespace Atannex\Enables;

use Spatie\Sluggable\HasSlug as Slug;
use Spatie\Sluggable\SlugOptions;

/**
 * Trait Slugging
 *
 * Provides automatic slug generation for Eloquent models using
 * the Spatie Sluggable package. This version also regenerates
 * the slug whenever the source field (e.g., title) is updated.
 */
trait Slugging
{
    use Slug;

    /**
     * Defines the field name used as the slug source.
     *
     * Example: "title"
     */
    protected function getSlugSource(): string
    {
        return $this->slugSource;
    }

    /**
     * Defines the field name where the generated slug
     * will be stored in the database.
     */
    protected function getSlugDestination(): string
    {
        return $this->slugDestination ?? 'slug';
    }

    /**
     * Configure the SlugOptions used to generate the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom($this->getSlugSource())
            ->saveSlugsTo($this->getSlugDestination())
            ->allowDuplicateSlugs();
    }

    /**
     * Determines whether the slug should be regenerated
     * on model update. Returning true ensures that if the
     * title (or source field) changes, the slug updates too.
     */
    public function slugsShouldBeGeneratedOnUpdate(): bool
    {
        return true;
    }
}
