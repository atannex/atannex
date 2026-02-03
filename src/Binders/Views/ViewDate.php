<?php

declare(strict_types=1);

namespace Atannex\Binders\Views;

use Illuminate\View\View;

trait ViewDate
{

    public function renderDateView(string $year, string $type = 'year', ?string $month = null): View
    {
        if (!preg_match('/^\d{4}$/', $year) || $year < 1900 || $year > date('Y') + 1) {
            abort(404, 'Invalid year');
        }

        $months = config('dates.months', []);
        $isMonthView = $type === 'month';

        if ($isMonthView) {
            if (!preg_match('/^\d{2}$/', $month) || $month < '01' || $month > '12') {
                abort(404, 'Invalid month');
            }

            $monthInt     = (int) $month;
            $displayValue = $months[$monthInt] ?? 'Unknown Month';
            $yearMonth    = "{$year}/{$month}";
            $seoTitle     = "Posts for {$displayValue} {$year}";
        } else {
            $displayValue = $year;
            $yearMonth    = $year;
            $seoTitle     = "Posts for the year {$year}";
        }

        return view('date', [
            'posts'         => $this->categoryService->postsByDate($yearMonth),
            'displayValue'  => $displayValue,
            'type'          => $type,
            'year'          => $year,
            'month'         => $month,
            'seoTitle'      => seo_title($seoTitle),
        ]);
    }
}
