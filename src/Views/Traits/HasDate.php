<?php

namespace Atannex\Views\Traits;

use Illuminate\View\View;

trait HasDate
{
    /**
     * Render posts filtered by date.
     */
    public function renderDateView(string $value, string $type, ?string $year = null): View
    {
        $months = config('dates.months');

        $isMonth = $type === 'month';
        $displayValue = $isMonth ? $months[(int) $value] : $value;
        $yearMonth = $isMonth
            ? ($year ?? date('Y')) . '/' . $value
            : $value;

        $seoTitle = $isMonth
            ? 'Posts for the month of ' . $displayValue
            : 'Posts for the year ' . $value;

        return $this->renderView(
            'date',
            [
                'posts' => $this->categoryService->postsByDate($yearMonth),
            ],
            seo_title($seoTitle)
        );
    }
}
