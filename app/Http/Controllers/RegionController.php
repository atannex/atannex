<?php

namespace App\Http\Controllers;

use Atannex\Binders\HasView;
use Atannex\Services\RegionService;
use Illuminate\View\View;
use Illuminate\Http\Exceptions\HttpResponseException;
use Atannex\Concerns\HasResolver;

/**
 * Class RegionController
 *
 * Resolves a slug into its corresponding content type and renders the appropriate view.
 * The resolution follows a fixed priority chain: region > category > tag > author > post > sub-region > date archive.
 */
class RegionController extends Controller
{
    use HasResolver;

    /**
     * RegionController constructor.
     *
     * Applies authentication, verification, and password confirmation middleware.
     *
     * @param RegionService $regionService  Handles business logic related to regions.
     * @param HasView       $viewBinder     Responsible for rendering views dynamically.
     */
    public function __construct(
        protected readonly RegionService $regionService,
        protected readonly HasView $viewBinder,
    ) {
        $this->middleware(['auth', 'verified', 'password.confirm']);
    }

    /**
     * Resolve the provided slug into a content entity and return the corresponding view.
     *
     * This method checks each type of content in priority order. The first successful match
     * results in rendering its view. If no matches are found, a 404 is returned.
     *
     * @param  string  $slug  The URL slug to resolve.
     * @return View            The rendered view for the resolved entity.
     *
     * @throws HttpResponseException 404 if no content matches the slug.
     */
    public function resolve(string $slug): View
    {
        // Ordered list of [resolver, renderer] pairs.
        $handlers = [
            // Main region (homepage)
            [
                fn($slug) => $this->regionService->getMainRegion($slug),
                fn()      => $this->viewBinder->renderRegionPageView($slug),
            ],

            // Category content
            [
                fn($slug) => $this->resolveCategory($slug),
                fn($category) => $this->viewBinder->renderCategoryView($category),
            ],

            // Tagged content
            [
                fn($slug) => $this->resolveTag($slug),
                fn($tag)  => $this->viewBinder->renderTagView($tag),
            ],

            // Author-specific content
            [
                fn($slug) => $this->resolveAuthor($slug),
                fn($author) => $this->viewBinder->renderAuthorView($author),
            ],

            // Individual post
            [
                fn($slug) => $this->resolvePost($slug),
                fn($post) => $this->viewBinder->renderPostShow($post->category, $slug),
            ],

            // Sub-region or location-specific pages
            [
                fn($slug) => $this->resolveRegion($slug),
                fn($region) => $this->viewBinder->renderRegionView($region),
            ],
        ];

        // Iterate through handlers and return the first matching view
        foreach ($handlers as [$resolver, $renderer]) {
            $result = $resolver($slug);

            if ($result) {
                return $renderer($result);
            }
        }

        // Fallback: Check for archive views (year/month)
        foreach (['year', 'month'] as $part) {
            if ($date = $this->resolvePostByDatePart($slug, $part)) {
                return $this->viewBinder->renderDateView($date['value'], $date['type']);
            }
        }

        // No match found: throw 404
        abort(404);
    }
}
