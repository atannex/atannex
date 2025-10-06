<?php

namespace Atannex;

use Atannex\Binders\PassPosts;
use App\Enums\Image;
use Atannex\Services\RegionService;
use Atannex\Helpers\HasMedia;
use Atannex\Services\TagService;

final class LebialemProvider extends PassPosts
{
    use HasMedia;

    public function __construct(
        protected readonly RegionService $regionService,
        protected readonly TagService $tagService,
        protected readonly PassPosts $passPosts,
    ) {}

    /**
     * Prepare shared view data for public region navigation.
     *
     * @return array<string, mixed> The navigation data array
     */
    public function getGlobalData(): array
    {
        return [
            'popularTags' => $this->tagService->getPopularTags(10),
            'logo' => $this->getGalleryImage(Image::LOGO()),
            'subscription' => $this->getGalleryImage(Image::SUBSCRIPTION()),
            'banner' => $this->getGalleryImage(Image::BANNER()),
            'favicon' => $this->getGalleryImage(Image::FAVICON()),
            'global_icons' => $this->getSocialMediaIcons(),
            'mainRegions' => $this->regionService->getAllMainRegions(),
            'categoryRegions' => $this->regionService->getAllCategoryRegions(),
            'recentPosts' => $this->getRecentPosts(2),
            'breaking' => $this->passPosts->getBreakingPosts(),
        ];
    }
}
