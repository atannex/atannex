<?php

declare(strict_types=1);

namespace Atannex\Binders\Views;

use App\Models\Regions\Region;
use Illuminate\View\View;

trait ViewRegion
{
    public function renderRegionView(Region $region): View
    {
        abort_unless($region->exists, 404);

        $this->resolveSection($region);

        $posts     = $this->categoryService->postsByRegion($region);
        $firstPost = $posts->first();
        $firstTag  = $firstPost?->tags->first();

        $popularTags = $firstTag
            ? $this->categoryService->popularTags($firstTag, 8)
            : collect();

        $relatedCategories = $firstTag
            ? $this->categoryService->relatedCategoriesByTag($firstTag)
            : collect();

        $recentPosts = $firstPost
            ? $this->categoryService->recentPosts($firstPost, 6)
            : collect();

        return view('region', [
            'region'            => $region,
            'posts'             => $posts,
            'recentPosts'       => $recentPosts,
            'popularTags'       => $popularTags,
            'relatedCategories' => $relatedCategories,
            'seoTitle'          => seo_title($region->name),
        ]);
    }
}
