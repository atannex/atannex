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
    ) {
    }

    /**
     * Prepare shared view data for public-facing layouts.
     *
     * @return array<string, mixed>
     */
    public function getGlobalData(): array
    {
        $regions = $this->regionService->getRootRegions();

        return [

            'logo'         => $this->getGalleryImage(Image::LOGO()),
            'favicon'      => $this->getGalleryImage(Image::FAVICON()),
            'banner'       => $this->getGalleryImage(Image::BANNER()),

            'global_icons' => $this->getSocialMediaIcons(),
            'popularTags'  => $this->tagService->getPopularTags(10),

            'mainRegions'      => $regions,
            'headerRegion'     => $regions->first(),
            'categoryRegions'  => $this->regionService->getRootCategoryRegions(),

            'recentPosts' => $this->getRecentPosts(2),
            'breaking'    => $this->getBreakingPosts(),
        ];
    }
}
