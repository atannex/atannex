<?php

namespace Atannex\Facades;

use App\Enums\Flag;
use App\Enums\Image;
use App\Models\Others\Gallery;
use App\Models\Others\SocialMedia;
use Atannex\Binders\HasPost;
use Atannex\Services\RegionService;
use Atannex\Services\TagService;
use Illuminate\Support\Collection;

final class Lebialem
{
    public function __construct(
        protected readonly RegionService $regionService,
        protected readonly TagService $tagService,
        protected readonly HasPost $getPosts,
    ) {}

    public function getGlobalData(): array
    {
        $regions = $this->regionService->getRootRegions();

        $recentPosts = $this->getPosts->hasRecentPosts(2);
        $breakingPosts = $this->getPosts->hasBreakingPosts();

        return [
            'cover' => $this->getGalleryImage(Image::COVER()),
            'logo' => $this->getGalleryImage(Image::LOGO()),
            'favicon' => $this->getGalleryImage(Image::FAVICON()),
            'banner' => $this->getGalleryImage(Image::BANNER()),

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

}
