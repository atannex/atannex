<?php

namespace App\Http\Controllers;

use App\Models\Posts\Post;
use App\Models\Regions\Category;
use App\Models\Regions\Employee;
use App\Models\Regions\Region;
use App\Models\Tags\Tag;
use Atannex\Binders\HasView;
use Atannex\Services\RegionService;
use Atannex\Traits\HasResolver;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\View\View;

/**
 * RegionController
 *
 * Handles all page-related requests.
 * This controller uses a resolver-based approach to determine
 * the type of content for a given slug and return the appropriate view.
 */
class RegionController extends Controller
{
    use HasResolver;

    /**
     * PageController constructor.
     *
     * @param  PageService  $pageService  Service to handle page-related business logic.
     * @param  HasView  $getView  Service to render views dynamically.
     */
    public function __construct(
        protected readonly RegionService $regionService,
        protected readonly HasView $getView,
    ) {

        $this->middleware(['auth', 'verified', 'password.confirm']);
    }

    /**
     * Resolve a slug to the correct resource and render its view.
     *
     * @param  string  $slug  The slug to resolve (e.g., page URL segment).
     *
     * @throws HttpResponseException Throws 404 if resource is not found.
     */
    public function resolve(string $slug): View
    {
        if ($this->regionService->getMainRegion($slug) instanceof Region) {

            return $this->getView->renderRegionPageView($slug);
        } elseif (($category = $this->resolveCategory($slug)) instanceof Category) {

            return $this->getView->renderCategoryView($category);
        } elseif (($tag = $this->resolveTag($slug)) instanceof Tag) {

            return $this->getView->renderTagView($tag);
        } elseif (($author = $this->resolveAuthor($slug)) instanceof Employee) {

            return $this->getView->renderAuthorView($author);
        } elseif (($post = $this->resolvePost($slug)) instanceof Post) {

            return $this->getView->renderPostShow($post->category, $slug);
        } elseif (($region = $this->resolveRegion($slug)) instanceof Region) {

            return $this->getView->renderRegionView($region);
        } else {

            foreach (['month', 'year'] as $part) {

                if ($date = $this->resolvePostByDatePart($slug, $part)) {

                    return $this->getView->renderDateView($date['value'], $date['type']);
                }
            }
        }

        abort(404);
    }
}
