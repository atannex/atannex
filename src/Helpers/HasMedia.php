<?php

namespace Atannex\Helpers;

use App\Enums\Flag;
use App\Enums\Icons;
use App\Enums\Image;
use App\Models\Others\Gallery;
use App\Models\Others\SocialMedia;
use Illuminate\Support\Collection;

trait HasMedia
{
    /**
     * Map a SocialMedia model instance to a simplified array representation.
     */
    protected function mapSocialMedia(SocialMedia $media): array
    {
        $platform = Icons::coerce($media->platform);

        return [
            'url'   => $media->url,
            'label' => $platform->getLabel(),
            'icon'  => $platform->getIcon(),
            'color' => $platform->getColor(),
        ];
    }

    /**
     * Retrieve all published global social media entries mapped with icons and metadata.
     */
    protected function getSocialMediaIcons(): Collection
    {
        return SocialMedia::query()
            ->where('flag', Flag::PUBLISHED)
            ->where('is_global', true)
            ->orderBy('order')
            ->get()
            ->map(fn(SocialMedia $media) => $this->mapSocialMedia($media))
            ->values();
    }

    /**
     * Retrieve a single published gallery image of the given type.
     */
    protected function getGalleryImage(Image $type): ?Gallery
    {
        return Gallery::flagged(Flag::PUBLISHED())
            ->whereType($type)
            ->first();
    }
}
