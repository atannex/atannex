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
        $months = [
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
            12 => 'December',
        ];

        $isMonth = $type === 'month';
        $displayValue = $isMonth ? ($months[(int) $value] ?? $value) : $value;
        $yearMonth = $isMonth ? ($year ?? date('Y')).'/'.$value : $value;

        $seoTitle = $isMonth
            ? 'Posts for the month of '.$displayValue
            : 'Posts for the year '.$value;

        return $this->renderView('date', [
            'posts' => $this->categoryService->postsByDate($yearMonth),
        ], seo_title($seoTitle));
    }
}
