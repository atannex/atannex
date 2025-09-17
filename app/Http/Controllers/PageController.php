<?php

namespace App\Http\Controllers;

use Illuminate\Http\Exceptions\HttpResponseException;
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

/**
 * PageController
 *
 * Handles all page-related requests.
 * This controller uses a resolver-based approach to determine
 * the type of content for a given slug and return the appropriate view.
 */
class PageController extends Controller
{
    use HasResolver;

    /**
     * PageController constructor.
     *
     * @param PageService $pageService Service to handle page-related business logic.
     * @param PassView $getView Service to render views dynamically.
     */
    public function __construct(
        protected readonly PageService $pageService,
        protected readonly PassView $getView,
    ) {

        $this->middleware(['auth', 'verified', 'password.confirm']);
    }

    /**
     * Resolve a slug to the correct resource and render its view.
     *
     * @param string $slug The slug to resolve (e.g., page URL segment).
     * @return View
     *
     * @throws HttpResponseException Throws 404 if resource is not found.
     */
    public function resolve(string $slug): View
    {
        // Check if the slug corresponds to the homepage.
        if ($this->pageService->getHomePage($slug) instanceof Page) {
            return $this->getView->renderPageView($slug);
        }

        // Check if the slug corresponds to a category.
        if (($category = $this->resolveCategory($slug)) instanceof Category) {
            return $this->getView->renderCategoryView($category);
        }

        // Check if the slug corresponds to a post tag.
        if (($postTag = $this->resolveTag($slug)) instanceof PostTag) {
            return $this->getView->renderTagView($postTag);
        }

        // Check if the slug corresponds to an author/employee.
        if (($author = $this->resolveAuthor($slug)) instanceof Employee) {
            return $this->getView->renderAuthorView($author);
        }

        // Check if the slug corresponds to a single post.
        if (($post = $this->resolvePost($slug)) instanceof Post) {
            return $this->getView->renderPostShow($post->category, $slug);
        }

        // Check if the slug corresponds to a region.
        if (($region = $this->resolveRegion($slug)) instanceof Region) {
            return $this->getView->renderRegionView($region);
        }

        // Attempt to resolve the slug as a date-based archive (month/year).
        foreach (['month', 'year'] as $part) {
            if ($date = $this->resolvePostByDatePart($slug, $part)) {
                return $this->getView->renderDateView($date['value'], $date['type']);
            }
        }

        // If no matching resource found, throw a 404 error.
        abort(404);
    }
}
