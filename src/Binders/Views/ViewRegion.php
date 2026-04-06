<?php

declare(strict_types=1);

namespace Atannex\Binders\Views;

use App\Models\Regions\Region;
use Atannex\Traits\ResolvesDynamicContent;
use Illuminate\View\View;

trait ViewRegion
{
    use ResolvesDynamicContent;

    /**
     * Render the region view.
     */
    public function renderRegionView(Region $region): View
    {
        $this->resolveSection($region);

        $posts = $this->regionService->postsByRegion($region);

        $recentPosts = $this->regionService
            ->recentPostsByRegion($region, 6);

        $popularTags = $this->categoryService
            ->popularTagsByRegion($region, 8);

        $relatedCategories = $this->categoryService
            ->relatedCategoriesByRegion($region);

        return view('region', [
            'region' => $region,
            'posts' => $posts,
            'recentPosts' => $recentPosts,
            'popularTags' => $popularTags,
            'relatedCategories' => $relatedCategories,
            'seoTitle' => seo_title($region->name),
        ]);
    }
}
