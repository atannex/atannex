<?php

namespace Atannex\Binders;

use Atannex\Facades\Atannex;
use Atannex\Services\CategoryService;
use Atannex\Services\RegionService;
use Atannex\Services\ShareService;
use Atannex\Services\TagService;
use Atannex\Views\Views;

/**
 * Class HasView
 *
 * Central binder for all view-related logic.
 * Loads the traits and services required to render posts, regions, categories,
 * tags, components, and other content types.
 *
 * This class aggregates essential services such as RegionService, TagService,
 * and CategoryService, ensuring consistent view rendering across the app.
 */
class HasView
{
    /**
     * Constructor
     *
     * @param  RegionService     $regionService     Service for region-related operations.
     * @param  Atannex           $atannex           Core provider for application-wide utilities.
     * @param  TagService        $tagService        Service for tag-related operations.
     * @param  CategoryService   $categoryService   Service for category-related operations.
     * @param  HasPost           $getPost           Helper for preparing post data.
     * @param  HasComponent      $getComponent      Helper for fetching reusable components.
     * @param  ShareService      $shareService      Service for share/metadata operations.
     */
    public function __construct(
        protected readonly RegionService $regionService,
        protected readonly Atannex $atannex,
        protected readonly TagService $tagService,
        protected readonly CategoryService $categoryService,
        protected readonly HasPost $getPost,
        protected readonly HasComponent $getComponent,
        protected readonly ShareService $shareService,
    ) {}

    /**
     * Traits containing view logic for regions, posts, categories, tags, authors,
     * dates, and shared components.
     */
    use Views;
}
