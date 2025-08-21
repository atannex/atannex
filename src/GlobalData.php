<?php

namespace Atannex;

use App\Enums\Image;
use Atannex\Binders\GetPost;
use Atannex\Services\PageService;
use Atannex\Helpers\MediaHelper;

final class GlobalData extends GetPost
{
    use MediaHelper;

    public function __construct(protected readonly PageService $pageService) {}

    /**
     * Prepare shared view data for public pages navigation.
     *
     * @return array<string, mixed> The navigation data array
     */
    public function getGlobalData(): array
    {
        return [
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
