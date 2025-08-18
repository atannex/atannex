<?php

namespace App\Http\Controllers;

use Atannex\Extension;
use Illuminate\View\View;
use App\Models\Posts\Post;
use Morfaw\Supports\Resolver;
use App\Models\Pages\Category;
use App\Models\Pivots\PostTag;
use Atangageih\Services\TagService;
use Atangageih\Services\PageService;
use Ngangagah\Parameters\RendersViews;
use Atangageih\Services\CategoryService;
use Atangageih\Services\SocialShareService;

class PageController extends Controller
{
    use RendersViews;
    use Resolver;

    public function __construct(
        protected readonly PageService $pageService,
        protected readonly CategoryService $categoryService,
        protected readonly Extension $extension,
        protected readonly TagService $tagService,
        protected readonly SocialShareService $socialShare
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

        if ($postTag = PostTag::with('tag', 'post.category')
            ->where('slug_path', $slug)->first()) {
            return $this->renderTagView($postTag);
        }

        if ($author = $this->resolveAuthorBySlug($slug)) {
            return $this->renderAuthorView($author);
        }

        if ($post = Post::where('slug_path', $slug)->first()) {
            return $this->renderPostShow($post->category, $slug);
        }

        abort(404);
    }
}
