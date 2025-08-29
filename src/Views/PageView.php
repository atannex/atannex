<?php

namespace Atannex\Views;

use Illuminate\View\View;
use Atannex\Views\Traits\Content;
use Atannex\Views\Traits\Render;

trait PageView
{
    use Content;
    use Render;

    /**
     * Render a static page.
     */
    public function renderPageView(string $slug): View
    {
        $page = $this->pageService->getHomePage($slug);
        $this->resolveContent($page);

        return $this->render('pages', ['page' => $page], $this->buildCommonViewData());
    }
}
