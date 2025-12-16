<?php

namespace Atannex\Views\Traits;

use Illuminate\View\View;

trait HasDate
{
    /**
     * Render posts filtered by date (year or month archive).
     *
     * Expected calls:
     * - Year:  renderDateView(year: '2024', type: 'year')
     * - Month: renderDateView(year: '2024', month: '03', type: 'month')
     */
    public function renderDateView(
        string $year,
        string $type = 'year',
        ?string $month = null
    ): View {

        $months = config('dates.months');

        if ($type === 'month') {

            $monthInt = (int) ltrim($month ?? '01', '0') ?: 1;

            $displayValue = $months[$monthInt] ?? 'Unknown Month';
            $seoTitle     = "Posts for {$displayValue} {$year}";
            $yearMonth    = "{$year}/" . str_pad($month ?? '01', 2, '0', STR_PAD_LEFT);
        } else {
            $displayValue = $year;
            $seoTitle     = "Posts for the year {$year}";
            $yearMonth    = $year;
        }

        $posts = $this->categoryService->postsByDate($yearMonth);

        return $this->renderView(
            'date',
            [
                'posts'        => $posts,
                'displayValue' => $displayValue,
                'type'         => $type,
                'year'         => $year,
                'month'        => $month,
            ],
            seo_title($seoTitle)
        );
    }
}
