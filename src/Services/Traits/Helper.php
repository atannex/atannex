<?php

namespace Atannex\Services\Traits;

use App\Enums\Flag;
use App\Enums\Icons;
use App\Enums\Image;
use App\Models\Others\Gallery;
use App\Models\Others\SocialMedia;
use Illuminate\Support\Collection;

trait Helper
{
    use HandlesExceptions;

    protected const DEFAULT_PAGINATION_LIMIT      = 50;
    protected const DEFAULT_RECENT_POSTS_LIMIT    = 5;
    protected const DEFAULT_POPULAR_TAGS_LIMIT    = 12;

    /**
     * Map a SocialMedia model instance to a simplified array representation.
     *
     * @param  SocialMedia  $media
     * @return array|null
     */
    protected function mapSocialMedia(SocialMedia $media): ?array
    {
        $platform = Icons::coerce($media->platform);

        if (! $platform) {
            return null;
        }

        return [
            'url'   => $media->url,
            'label' => $platform->getLabel(),
            'icon'  => $platform->getIcon(),
            'color' => $platform->getColor(),
        ];
    }

    /**
     * Retrieve all published global social media entries mapped with icons and metadata.
     *
     * @return Collection
     */
    protected function getSocialMediaIcons(): Collection
    {
        return SocialMedia::query()
            ->where('flag', Flag::PUBLISHED)
            ->where('is_global', true)
            ->orderBy('order')
            ->get()
            ->map(fn(SocialMedia $media) => $this->mapSocialMedia($media))
            ->filter()
            ->values();
    }


    /**
     * Retrieve a single published gallery image of the given type.
     *
     * @param  Image  $type
     * @return Gallery|null
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    protected function getGalleryImage(Image $type): ?Gallery
    {
        return Gallery::flagged(Flag::PUBLISHED())
            ->whereType($type)
            ->first();
    }
}
