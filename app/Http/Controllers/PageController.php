<?php

namespace App\Http\Controllers;

use Atannex\Extension;
use Illuminate\View\View;
use Morfaw\Supports\Resolver;
use App\Models\Pages\Category;
use App\Models\Posts\Post;
use Ngangagah\Parameters\RendersViews;
use Atangageih\Services\PageService;
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

    public function resolve(string $slug): View
    {

        if ($this->pageService->getHomePage($slug)) {
            return $this->renderPageView($slug);
        }

        if ($category = Category::where('slug_path', $slug)->first()) {
            return $this->renderCategoryView($category);
        }

        // 3. Tag page (uncomment and implement if needed)
        // if ($tag = PostTag::where('slug_path', $slug)->first()) {
        //     return $this->renderTagView($tag);
        // }

        if ($author = $this->resolveAuthorBySlug($slug)) {
            return $this->renderAuthorView($author);
        }

        if ($post = Post::where('slug_path', $slug)->first()) {
            return $this->renderPostShow($post->category, $slug);
        }

        abort(404);
    }
}
