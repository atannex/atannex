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

    public function show(string $slug_path, string $slug) {
        // $categoryModel = $this->findCategory($slug_path);
        // $postModule = $this->findPostModule($categoryModel, $slug);

        return view('shows.index');
    }

    // private function findCategory(string $slug): Category
    // {
    //     return Category::where('slug', $slug)->first();
    // }

    // private function findPostModule(Category $category, string $slug): PostModule
    // {
    //     return PostModule::whereHas('post', function ($query) use ($slug, $category) {
    //         $query->where('slug', $slug)
    //             ->where('category_id', $category->slug); })
    //         ->with('post')
    //         ->first();
    // }

    private function abortNotFound()
    {
        abort(404);
    }

}
