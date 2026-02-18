<?php

declare(strict_types=1);

namespace Atannex\Binders\Views;

use Atannex\Traits\HandlesPostDateResolution;
use Illuminate\View\View;

trait ViewDate
{
    use HandlesPostDateResolution;

    /**
     * Render a date-based archive view (year or month).
     *
     * Expects a slug in the form:
     *  - YYYY
     *  - YYYY/MM
     */
    public function renderDateView(string $slug): View
    {
        $resolution = $this->resolvePostArchiveBySlug($slug);

        if ($resolution === null) {
            abort(404);
        }

        $year = (string) $resolution['year'];
        $month = $resolution['month'] !== null
            ? str_pad((string) $resolution['month'], 2, '0', STR_PAD_LEFT)
            : null;

        if ($resolution['type'] === 'month') {
            $monthInt = (int) $month;
            $monthName = config("dates.months.{$monthInt}", 'Unknown Month');
            $period = "{$year}/{$month}";
            $displayValue = "{$monthName} {$year}";
            $seoTitle = "Posts for {$displayValue}";
        } else {
            $period = $year;
            $displayValue = $year;
            $seoTitle = "Posts for the year {$year}";
        }

        return view('date', [
            'posts' => $this->categoryService->postsByDate($period),
            'displayValue' => $displayValue,
            'period' => $period,
            'type' => $resolution['type'],
            'year' => $year,
            'month' => $month,
            'seoTitle' => seo_title($seoTitle),
        ]);
    }
}
