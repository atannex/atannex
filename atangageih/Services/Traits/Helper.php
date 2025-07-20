<?php

namespace Atangageih\Services\Traits;

use App\Enums\Flag;
use App\Enums\Icons;
use App\Enums\Image;
use App\Models\Others\Gallery;
use App\Models\Others\SocialMedia;
use Illuminate\Support\Collection;

trait Helper
{
    use HandlesExceptions;

    private const DEFAULT_PAGINATION_LIMIT = 50;
    private const DEFAULT_RECENT_POSTS_LIMIT = 5;
    private const DEFAULT_POPULAR_TAGS_LIMIT = 12;

    protected function mapSocialMedia(SocialMedia $media): ?array
    {
        return $this->safely(function () use ($media) {
            $platformEnum = Icons::coerce($media->platform);

            if (!$platformEnum) {
                throw new \UnexpectedValueException("Invalid platform: {$media->platform}");
            }

            return [
                'url'   => $media->url,
                'label' => $platformEnum->getLabel(),
                'icon'  => $platformEnum->getIcon(),
                'color' => $platformEnum->getColor(),
            ];
        }, 'Failed to map social media', ['media_id' => $media->id ?? null]);
    }

    protected function getSocialMediaIcons(): Collection
    {
        return $this->safely(function () {
            return SocialMedia::flagged(Flag::PUBLISHED())
                ->global()
                ->orderBy('order')
                ->get()
                ->map(fn($media) => $this->mapSocialMedia($media))
                ->filter()
                ->values();
        }, 'Failed to retrieve social media icons', collect());
    }

    protected function getGalleryImage(Image $type): ?Gallery
    {
        return $this->safely(function () use ($type) {
            return Gallery::flagged(Flag::PUBLISHED())
                ->whereType($type)
                ->firstOrFail();
        }, "Failed to fetch gallery image of type {$type->value}");
    }
}
