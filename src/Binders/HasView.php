<?php

namespace Atannex\Binders;

use Atannex\Facades\Atannex;
use Atannex\Services\CategoryService;
use Atannex\Services\RegionService;
use Atannex\Services\ShareService;
use Atannex\Services\TagService;
use Atannex\Views\Views;

/**
 * Class GetView
 *
 * Acts as a centralized binder for view-related functionalities.
 * Aggregates multiple view traits (AuthorView, CategoryView, PageView, etc.)
 * and provides access to essential services such as PageService, TagService,
 * and CategoryService. Facilitates rendering of various views like posts,
 * pages, tags, authors, regions, and dates.
 */
class HasView
{
    /**
     * Constructor
     *
     * Injects the services and binders required for view rendering.
     *
     * @param  PageService  $pageService  Service for page-related operations.
     * @param  Atannex  $atannex  Core provider for application-wide utilities.
     * @param  TagService  $tagService  Service for tag-related operations.
     * @param  CategoryService  $categoryService  Service for category-related operations.
     * @param  HasPost  $getPost  Helper for fetching and preparing post data.
     * @param  HasComponent  $getComponent  Helper for fetching reusable components.
     */
    public function __construct(
        protected readonly RegionService $pageService,
        protected readonly Atannex $atannex,
        protected readonly TagService $tagService,
        protected readonly CategoryService $categoryService,
        protected readonly HasPost $getPost,
        protected readonly HasComponent $getComponent,
        protected readonly ShareService $shareService,
    ) {}

    /**
     * Traits providing modular view logic.
     * Each trait contains methods for rendering or preparing specific view types.
     */
    use Views;
}
