<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Atannex\Binders\HasView;
use Atannex\Services\RegionService;
use Atannex\Concerns\HasResolver;

class RegionController extends Controller
{
    use HasResolver;

    public function __construct(
        protected readonly RegionService $regionService,
        protected readonly HasView $viewBinder,
    ) {
        $this->middleware('pages');
    }

    /**
     * Resolve a slug into its corresponding content entity.
     */
    public function resolve(string $slug): View
    {
        if ($region = $this->regionService->getRegionBySlug($slug)) {
            return $this->viewBinder->renderRegionView($region);
        }

        if ($tag = $this->resolveTag($slug)) {
            return $this->viewBinder->renderTagView($tag);
        }

        if ($author = $this->resolveAuthor($slug)) {
            return $this->viewBinder->renderAuthorView($author);
        }

        if ($date = $this->resolvePostByDate($slug)) {
            return $this->viewBinder->renderDateView(
                year: $date['year'],
                month: $date['type'] === 'month' ? $date['month'] : null,
                type: $date['type']
            );
        }

        if ($post = $this->resolvePost($slug)) {
            return $this->viewBinder->renderPostShow($post->category, $slug);
        }

        if ($category = $this->resolveCategory($slug)) {
            return $this->viewBinder->renderCategoryView($category);
        }

        abort(404);
    }
}
