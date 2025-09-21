<?php

namespace Atannex\Views\Traits;

use Illuminate\View\View;

trait CanRender
{
    /**
     * Render a view with optional SEO title and additional data.
     */
    protected function renderView(string $view, array $data = [], string $seoTitle = ''): View
    {
        if ($seoTitle !== '' && $seoTitle !== '0') {
            $data['seoTitle'] = $seoTitle;
        }

        return view($view, $data);
    }
}
