<?php

namespace Atannex\Facades;

use App\Enums\Image;
use Atannex\Binders\HasPost;
use Atannex\Helpers\HasMedia;
use Atannex\Services\RegionService;
use Atannex\Services\TagService;

final class Lebialem
{
    use HasMedia;

    /**
     * Initialize the Lebialem facade with its required services.
     */
    public function __construct(
        protected readonly RegionService $regionService,
        protected readonly TagService $tagService,
        protected readonly HasPost $getPosts,
    ) {}

    /**
     * Assemble shared data used by public-facing layouts.
     *
     * The returned array contains view-ready resources and collections required by
     * public layouts:
     * - `logo`, `favicon`, `banner`: URLs or media entries for site branding.
     * - `global_icons`: social media icon set.
     * - `popularTags`: top tags (limited to 10).
     * - `mainRegions`: collection of root regions.
     * - `headerRegion`: the first region from `mainRegions`.
     * - `categoryRegions`: root category regions.
     * - `recentPosts`: recent posts as provided by the injected HasPost service (2 units).
     * - `breaking`: breaking posts indicator from the injected HasPost service.
     *
     * @return array<string, mixed> Associative array of layout data keyed as described above.
     */
    public function getGlobalData(): array
    {
        $regions = $this->regionService->getRootRegions();

        $recentPosts = $this->getPosts->hasRecentPosts(2);
        $breakingPosts = $this->getPosts->hasBreakingPosts();

        return [
            'cover'           => $this->getGalleryImage(Image::COVER()),
            'logo'            => $this->getGalleryImage(Image::LOGO()),
            'favicon'         => $this->getGalleryImage(Image::FAVICON()),
            'banner'          => $this->getGalleryImage(Image::BANNER()),

            'global_icons'    => $this->getSocialMediaIcons(),
            'popularTags'     => $this->tagService->getPopularTags(10),

            'mainRegions'     => $regions,
            'headerRegion'    => $regions->first(),
            'categoryRegions' => $this->regionService->getRootCategoryRegions(),

            'recentPosts' => $recentPosts,

            'breaking'    => $breakingPosts->isNotEmpty() ? $breakingPosts : $recentPosts,
        ];
    }
}
