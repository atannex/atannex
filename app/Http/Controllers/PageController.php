<?php

namespace App\Http\Controllers;

use Atangageih\Services\CategoryService;
use Illuminate\View\View;
use Illuminate\Http\Response;
use Atangageih\Services\PageService;
use Atannex\Extension;
use Ngangagah\Parameters\RendersViews;
use Morfaw\Supports\Resolver;

class PageController extends Controller
{
    use RendersViews;
    use Resolver;

    public function __construct(
        protected readonly PageService $pageService,
        protected readonly CategoryService $categoryService,
        protected readonly Extension $extension
    ) {
        $this->middleware('auth');
    }

    /**
     * Handle a page request based on the slug.
     *
     * @param string $slug
     * @return View|Response
     */
    public function __invoke(string $slug): View|Response
    {

        if ($category = $this->resolveCategoryFromSlugs($slug)) {
            return $this->renderCategoryView($category);
        }

        if ($this->pageService->getHomePage($slug)) {
            return $this->renderPageView($slug);
        }

        if ($author = $this->resolveAuthorBySlug($slug)) {
            return $this->renderAuthorView($author);
        }

        abort(404);
    }
}
