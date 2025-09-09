<?php

namespace Atannex\Binders;

use Atannex\AtannexProvider;
use Atannex\Views\TagView;
use Atannex\Views\PageView;
use Atannex\Views\AuthorView;
use Atannex\Views\ContentViews;
use Atannex\Views\PostShowView;
use Atannex\Services\TagService;
use Atannex\Services\PageService;
use Atannex\Services\CategoryService;
use Atannex\Views\DateView;
use Atannex\Views\RegionView;

/**
 * Class GetView
 *
 * Acts as a centralized binder for view-related functionalities.
 * Aggregates multiple view traits (AuthorView, CategoryView, PageView, etc.)
 * and provides access to essential services such as PageService, TagService,
 * and CategoryService. Facilitates rendering of various views like posts,
 * pages, tags, authors, regions, and dates.
 *
 * @package Atannex\Binders
 */
class GetView
{
    /**
     * Constructor
     *
     * Injects the services and binders required for view rendering.
     *
     * @param PageService $pageService Service for page-related operations.
     * @param AtannexProvider $atannex Core provider for application-wide utilities.
     * @param TagService $tagService Service for tag-related operations.
     * @param CategoryService $categoryService Service for category-related operations.
     * @param GetPost $getPost Helper for fetching and preparing post data.
     * @param GetComponent $getComponent Helper for fetching reusable components.
     */
    public function __construct(
        protected readonly PageService $pageService,
        protected readonly AtannexProvider $atannex,
        protected readonly TagService $tagService,
        protected readonly CategoryService $categoryService,
        protected readonly GetPost $getPost,
        protected GetComponent $getComponent,
    ) {}

    /**
     * Traits providing modular view logic.
     * Each trait contains methods for rendering or preparing specific view types.
     */

    use ContentViews;
}
