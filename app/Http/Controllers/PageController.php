<?php

namespace App\Http\Controllers;

use Atannex\Extension;
use Illuminate\View\View;
use Morfaw\Supports\Resolver;
use App\Models\Pages\Category;
use App\Models\Modules\PostModule;
use Atangageih\Services\PageService;
use Ngangagah\Parameters\RendersViews;
use Atangageih\Services\CategoryService;

class PageController extends Controller
{
    use RendersViews, Resolver;

    public function __construct(
        protected readonly PageService $pageService,
        protected readonly CategoryService $categoryService,
        protected readonly Extension $extension
    ) {
        $this->middleware(['auth', 'verified', 'password.confirm']);
    }

    /**
     * Resolve the requested slug and render the appropriate view.
     *
     * @param string $slug
     * @return \Illuminate\View\View|\Illuminate\Http\Response
     */
    public function resolve(string $slug)
    {

        if ($view = $this->tryRenderHomePage($slug)) {
            return $view;
        }

        if ($category = $this->resolveCategoryFromSlugs($slug)) {
            return $this->renderCategoryView($category);
        }

        if ($author = $this->resolveAuthorBySlug($slug)) {
            return $this->renderAuthorView($author);
        }

        return $this->abortNotFound();
    }

    private function tryRenderHomePage(string $slug): ?View
    {
        return $this->pageService->getHomePage($slug)
            ? $this->renderPageView($slug)
            : null;
    }

    public function show(
        string $year,
        string $month,
        string $day,
        string $category,
        string $slug
    ) {
        $categoryModel = $this->findCategory($category);
        $postModule = $this->findPostModule($categoryModel, $slug, $year, $month, $day);

        return view('shows.index', ['module' => $postModule]);
    }

    private function findCategory(string $slug): Category
    {
        return Category::where('slug', $slug)->firstOrFail();
    }

    private function findPostModule(Category $category, string $slug, string $year, string $month, string $day): PostModule
    {
        return PostModule::whereHas('post', function ($query) use ($slug, $category, $year, $month, $day) {
            $query->where('slug', $slug)
                ->where('category_id', $category->id)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->whereDay('created_at', $day);
        })->with('post')->firstOrFail();
    }

    private function abortNotFound()
    {
        abort(404);
    }

}
