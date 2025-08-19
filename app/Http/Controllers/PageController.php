<?php

namespace App\Http\Controllers;

use Atannex\Extension;
use Illuminate\View\View;
use Morfaw\Supports\Resolver;
use Ngangagah\Handlers\GetPosts;
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
        protected readonly SocialShareService $socialShare,
        protected readonly GetPosts $postService
    ) {
        $this->middleware(['auth', 'verified', 'password.confirm']);
    }

    public function resolve(string $slug): View
    {
        if ($this->pageService->getHomePage($slug)) {
            return $this->renderPageView($slug);
        }

        if ($category = $this->resolveCategory($slug)) {
            return $this->renderCategoryView($category);
        }

        if ($postTag = $this->resolveTag($slug)) {
            return $this->renderTagView($postTag);
        }

        if ($author = $this->resolveAuthor($slug)) {
            return $this->renderAuthorView($author);
        }

        if ($post = $this->resolvePost($slug)) {
            return $this->renderPostShow($post->category, $slug);
        }

        abort(404);
    }
}
