<?php

namespace App\Http\Controllers;

use App\Models\Pages\Page;
use App\Models\Pages\Category;
use App\Models\Pivots\PostTag;
use App\Models\Regions\Employee;
use App\Models\Posts\Post;
use App\Models\Regions\Region;
use Illuminate\View\View;
use Atannex\Traits\HasResolver;
use Atannex\Services\PageService;
use Atannex\Binders\PassView;

class PageController extends Controller
{
    use HasResolver;

    public function __construct(
        protected readonly PageService $pageService,
        protected readonly PassView $getView,
    ) {
        $this->middleware(['auth', 'verified', 'password.confirm']);
    }

    public function resolve(string $slug): View
    {
        if ($this->pageService->getHomePage($slug) instanceof Page) {
            return $this->getView->renderPageView($slug);
        }

        if (($category = $this->resolveCategory($slug)) instanceof Category) {
            return $this->getView->renderCategoryView($category);
        }

        if (($postTag = $this->resolveTag($slug)) instanceof PostTag) {
            return $this->getView->renderTagView($postTag);
        }

        if (($author = $this->resolveAuthor($slug)) instanceof Employee) {
            return $this->getView->renderAuthorView($author);
        }

        if (($post = $this->resolvePost($slug)) instanceof Post) {
            return $this->getView->renderPostShow($post->category, $slug);
        }

        if (($region = $this->resolveRegion($slug)) instanceof Region) {
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
