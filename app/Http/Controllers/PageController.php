<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Atannex\Traits\Resolver;
use Atannex\Services\PageService;
use Atannex\Binders\GetView;

class PageController extends Controller
{
    use Resolver;

    public function __construct(
        protected readonly PageService $pageService,
        protected readonly GetView $getView,
    ) {
        $this->middleware(['auth', 'verified', 'password.confirm']);
    }

    public function resolve(string $slug): View
    {
        if ($this->pageService->getHomePage($slug)) {
            return $this->getView->renderPageView($slug);
        }

        if ($category = $this->resolveCategory($slug)) {
            return $this->getView->renderCategoryView($category);
        }

        if ($postTag = $this->resolveTag($slug)) {
            return $this->getView->renderTagView($postTag);
        }

        if ($author = $this->resolveAuthor($slug)) {
            return $this->getView->renderAuthorView($author);
        }

        if ($post = $this->resolvePost($slug)) {
            return $this->getView->renderPostShow($post->category, $slug);
        }

        if ($region = $this->resolveRegion($slug)) {
            return $this->getView->renderRegionView($region);
        }

        foreach (['month', 'year'] as $part) {
            if ($date = $this->resolvePostByDatePart($slug, $part)) {
                return $this->getView->renderDateView($date['value'], $date['type']);
            }
        }


        abort(404);
    }
}
