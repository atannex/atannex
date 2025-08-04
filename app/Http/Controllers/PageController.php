<?php

namespace App\Http\Controllers;

use App\Models\Modules\PostModule;
use Atannex\Extension;
use Illuminate\View\View;
use App\Models\Posts\Post;
use Illuminate\Http\Response;
use Morfaw\Supports\Resolver;
use Atangageih\Services\PageService;
use Ngangagah\Parameters\RendersViews;
use Atangageih\Services\CategoryService;

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

        if (str_contains($slug, '/')) {
            [$categorySlug, $postSlug] = explode('/', $slug, 2);

            $postModule = PostModule::whereHas('post', function ($postQuery) use ($postSlug, $categorySlug) {
                $postQuery->where('slug', $postSlug)
                    ->whereHas('category', function ($categoryQuery) use ($categorySlug) {
                        $categoryQuery->where('slug_path', $categorySlug);
                    });
            })->first();

            if ($postModule) {
                return view('shows.index', ['post' => $postModule->post]);
            }
        }

        abort(404);
    }
}
