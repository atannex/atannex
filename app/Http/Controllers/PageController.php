<?php

namespace App\Http\Controllers;

use Atannex\Extension;
use Illuminate\View\View;
use Morfaw\Supports\Resolver;
use App\Models\Pages\Category;
use App\Models\Modules\PostModule;
use App\Models\Pivots\PostTag;
use App\Models\Posts\Post;
use Atangageih\Services\PageService;
use Ngangagah\Parameters\RendersViews;
use Atangageih\Services\CategoryService;
use Atangageih\Services\TagService;

class PageController extends Controller
{
    use RendersViews, Resolver;

    public function __construct(
        protected readonly PageService $pageService,
        protected readonly CategoryService $categoryService,
        protected readonly Extension $extension,
        protected readonly TagService $tagService
    ) {
        $this->middleware(['auth', 'verified', 'password.confirm']);
    }

    public function resolve(string $slug)
    {

        if ($view = $this->tryRenderHomePage($slug)) {
            return $view;
        }

        if ($category = Category::where('slug_path', $slug)->first()) {
            return $this->renderCategoryView($category);
        }

        if ($author = $this->resolveAuthorBySlug($slug)) {
            return $this->renderAuthorView($author);
        }

        if ($posts = Post::where('slug_path', $slug)->first()) {
            $module = PostModule::whereHas('post', function ($query) use ($slug) {
                $query->where('slug_path', $slug);
            })->with('post')->firstOrFail();

            return view('shows.index', compact('module'));
        }

        if ($tag = PostTag::where('slug_path', $slug)->first()) {
            return $this->renderCategoryView($tag);
        }

        return $this->abortNotFound();
    }

    private function tryRenderHomePage(string $slug): ?View
    {
        return $this->pageService->getHomePage($slug)
            ? $this->renderPageView($slug)
            : null;
    }

    private function abortNotFound()
    {
        abort(404);
    }
}
