<?php

namespace Atannex\Facades;

use App\Enums\Image;
use Atannex\Binders\HasPost;
use Atannex\Helpers\HasMedia;
use Atannex\Services\RegionService;
use Atannex\Services\TagService;

final class Lebialem extends HasPost
{
    use HasMedia;

    public function __construct(
        protected readonly RegionService $regionService,
        protected readonly TagService $tagService,
        protected readonly HasPost $hasPost,
    ) {}

    /**
     * Prepare shared view data for public region navigation.
     *
     * @return array<string, mixed>
     */
    public function getGlobalData(): array
    {
        return [
            'popularTags'     => $this->tagService->getPopularTags(10),
            'logo'            => $this->getGalleryImage(Image::LOGO()),
            'subscription'    => $this->getGalleryImage(Image::SUBSCRIPTION()),
            'banner'          => $this->getGalleryImage(Image::BANNER()),
            'favicon'         => $this->getGalleryImage(Image::FAVICON()),
            'global_icons'    => $this->getSocialMediaIcons(),

            'mainRegions'         => $this->regionService->getRootRegions(),
            'categoryRegions' => $this->regionService->getRootCategoryRegions(),

            'recentPosts'     => $this->getRecentPosts(2),
            'breaking'        => $this->hasPost->getBreakingPosts(),
        ];
    }
}
