<?php

namespace Atannex\Facades;

use App\Enums\Image;
use App\Models\Posts\Post;
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
     * @return array<string, mixed> The navigation data array
     */
    public function getGlobalData(?Post $post = null): array
    {
        return [
            'globalPost' => $post instanceof Post ? $this->hasPost->getModulePostBySlug($post->slug) : null,
            'popularTags' => $this->tagService->getPopularTags(10),
            'logo' => $this->getGalleryImage(Image::LOGO()),
            'subscription' => $this->getGalleryImage(Image::SUBSCRIPTION()),
            'banner' => $this->getGalleryImage(Image::BANNER()),
            'favicon' => $this->getGalleryImage(Image::FAVICON()),
            'global_icons' => $this->getSocialMediaIcons(),
            'mainRegions' => $this->regionService->getAllMainRegions(),
            'categoryRegions' => $this->regionService->getAllCategoryRegions(),
            'recentPosts' => $this->getRecentPosts(2),
            'breaking' => $this->hasPost->getBreakingPosts(),
        ];
    }
}
