<?php

namespace Atangageih\Services\Traits;

use App\Enums\Flag;
use App\Enums\Icons;
use App\Enums\Image;
use App\Models\Others\Gallery;
use App\Models\Others\SocialMedia;
use Illuminate\Support\Collection;

/**
 * Trait providing helper methods for common operations.
 *
 * Includes utilities for mapping social media data and default configuration constants.
 */
trait Helper
{
    /**
     * Default number of posts per page for pagination.
     */
    private const DEFAULT_PAGINATION_LIMIT = 50;

    /**
     * Default number of recent posts to retrieve.
     */
    private const DEFAULT_RECENT_POSTS_LIMIT = 5;

    /**
     * Default number of popular tags to retrieve.
     */
    private const DEFAULT_POPULAR_TAGS_LIMIT = 12;

    /**
     * Map a SocialMedia model to an array with enum metadata.
     *
     * Converts a SocialMedia instance into an array containing the URL and metadata
     * (label, icon, and color) derived from the associated SocialMedia enum.
     *
     * @param SocialMedia $media The social media model to map.
     * @return array<string, string> An array containing URL, label, icon, and color.
     */
    protected function mapSocialMedia(SocialMedia $media): array
    {
        $platformEnum = Icons::coerce($media->platform);

        return [
            'url'   => $media->url,
            'label' => $platformEnum->getLabel(),
            'icon'  => $platformEnum->getIcon(),
            'color' => $platformEnum->getColor(),
        ];
    }


    protected function getSocialMediaIcons(): Collection
    {
        $query = SocialMedia::flagged(Flag::PUBLISHED())
            ->global()
            ->orderBy('order');

        return $query->get()
            ->map(fn($media) => $this->mapSocialMedia($media))
            ->filter()
            ->values();
    }

    /**
     * Reusable method to fetch a single gallery image by type.
     */
    protected function getGalleryImage(Image $type): ?Gallery
    {
        return Gallery::flagged(Flag::PUBLISHED())
            ->whereType($type)
            ->first();
    }
}
