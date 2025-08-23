<?php

namespace Atannex\Views;

use Illuminate\View\View;
use App\Models\Posts\Post;
use Atannex\Views\Traits\Render;

trait DateView
{
    use Render;

    /**
     * Render the date page.
     *
     * @param Post $post display all post based on the date
     * @return View
     */
    public function renderDateView(Post $post): View
    {
        $year  = $post->published_at->format('Y');
        $month = $post->published_at->format('m');

        return $this->render('date', [
            'seoTitle' => $year
                ? seo_title("Posts for the year - {$year}")
                : seo_title("Posts for the month of {$month}"),
            'post'     => $post,
            // 'posts'    => $this->categoryService->getPostsByDate($month, $year),
        ], $this->buildCommonViewData());
    }
}
