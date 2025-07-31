<?php

namespace Ngangagah\Handlers;

use App\Enums\Image;
use Atangageih\Services\PageService;
use Atangageih\Services\Traits\Helper;

final class Navigation extends GetPosts
{
    use Helper;

    public function __construct(protected readonly PageService $pageService) {}

    /**
     * Prepare shared view data for public pages navigation.
     *
     * @return array<string, mixed> The navigation data array
     */
    public function getPageNavigation(): array
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
