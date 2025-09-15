<?php

namespace Atannex;

use Atannex\Binders\PassPosts;
use App\Enums\Image;
use Atannex\Services\PageService;
use Atannex\Helpers\HasMedia;
use Atannex\Services\TagService;

final class LebialemProvider extends PassPosts
{
    use HasMedia;

    public function __construct(
        protected readonly PageService $pageService,
        protected readonly TagService $tagService,
    ) {}

    /**
     * Prepare shared view data for public pages navigation.
     *
     * @return array<string, mixed> The navigation data array
     */
    public function getGlobalData(): array
    {
        return [
            'popularTags' =>$this->tagService->getPopularTags(10),
            'logo' => $this->getGalleryImage(Image::LOGO()),
            'banner' => $this->getGalleryImage(Image::BANNER()),
            'favicon' => $this->getGalleryImage(Image::FAVICON()),
            'global_icons' => $this->getSocialMediaIcons(),
            'home' => $this->pageService->getAllHomePages(),
            'navs' => $this->pageService->getAllCategoryPages(),
            'recentPosts'  => $this->getRecentPublishedPosts(4),
        ];
    }
}
