<?php

namespace Atannex\Views;

use Illuminate\View\View;
use Atannex\Views\Traits\Render;

trait DateView
{
    use Render;

    /** ------------------------------
     * Render date view with SEO and posts
     * ----------------------------- */
    public function renderDateView(string $value, string $type, ?string $year = null): View
    {
        $isMonth = $type === 'month';

        static $months = [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December'
        ];

        $displayValue = $isMonth ? ($months[(int)$value] ?? '') : $value;
        $seoTitle = $isMonth
            ? "Posts for the month of {$displayValue}"
            : "Posts for the year - {$value}";

        $yearMonth = $isMonth ? ($year ?? date('Y')) . '/' . $value : $value;

        return $this->render(
            'date',
            [
                'seoTitle' => seo_title($seoTitle),
                'posts'    => $this->categoryService->getPostsByDate($yearMonth)
            ],
            $this->buildCommonViewData()
        );
    }
}
