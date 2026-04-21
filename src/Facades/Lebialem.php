<?php

namespace Atannex\Facades;

use App\Enums\Flag;
use App\Enums\Image;
use App\Models\Others\Gallery;
use App\Models\Others\SocialMedia;
use Atannex\Services\PostService;
use Atannex\Services\RegionService;
use Atannex\Services\TagService;
use Illuminate\Support\Collection;

final class Lebialem
{
    public function __construct(
        protected readonly RegionService $regionService,
        protected readonly TagService $tagService,
        protected readonly PostService $post_service,
    ) {}

    public function getGlobalData(): array
    {
        $regions = $this->regionService->getRootRegions();

        $recentPosts = $this->post_service->getRecentPosts(2);
        $breakingPosts = $this->post_service->getLatestBreakingPosts();

        return [
            'cover' => $this->getGalleryImage(Image::COVER()),
            'logo' => $this->getGalleryImage(Image::LOGO()),
            'favicon' => $this->getGalleryImage(Image::FAVICON()),
            'banner' => $this->getGalleryImage(Image::BANNER()),
            'galleries' => $this->getGalleryImagesByType(Image::IMAGE()),

            'global_icons' => $this->getSocialMediaIcons(),
            'popularTags' => $this->tagService->popularTagsGlobal(10),

            'mainRegions' => $regions,
            'headerRegion' => $regions->first(),
            'categoryRegions' => $this->regionService->getRootCategoryRegions(),

            'recentPosts' => $recentPosts,

            'breaking' => $breakingPosts->isNotEmpty() ? $breakingPosts : $recentPosts,
        ];
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
            ->map(fn(SocialMedia $media) => map_social_media($media))
            ->values();
    }

    protected function getGalleryImagesByType(Image $type): Collection
    {
        return Gallery::query()
            ->where('flag', Flag::PUBLISHED)
            ->where('type', $type)
            ->orderBy('order')
            ->limit(16)
            ->get();
    }
}
