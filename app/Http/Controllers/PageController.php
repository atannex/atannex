<?php

namespace App\Http\Controllers;

use Atannex\Atannex;
use Illuminate\View\View;
use Atannex\Traits\Resolver;
use Atannex\Binders\GetPosts;
use Atannex\Services\TagService;
use Atannex\Services\PageService;
use Atannex\Services\CategoryService;
use Atannex\Views\ViewFacade;

class PageController extends Controller
{
    use ViewFacade;
    use Resolver;

    public function __construct(
        protected readonly PageService $pageService,
        protected readonly CategoryService $categoryService,
        protected readonly Atannex $atannex,
        protected readonly TagService $tagService,
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
