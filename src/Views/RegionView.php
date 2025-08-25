<?php

namespace Atannex\Views;

use Illuminate\View\View;
use App\Models\Regions\Region;
use Atannex\Views\Traits\Render;

trait RegionView
{
    use Render;

    /**
     * Render the Region page.
     *
     * @param Region $region The region model to display
     */
    public function renderRegionView(Region $region): View
    {
        $first = $region->name;

        return $this->render('region', [
            'seoTitle' => seo_title($first),
            'region'   => $region,
            'posts'    => $this->categoryService->getPostsByRegion($region)
        ], $this->buildCommonViewData());
    }
}
