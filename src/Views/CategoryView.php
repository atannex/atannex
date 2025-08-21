<?php

namespace Atannex\Views;

use Illuminate\View\View;
use App\Models\Pages\Category;
use Atannex\Views\Traits\Render;

trait CategoryView
{
    use Render;

    /**
     * Render a category page with related content.
     */
    public function renderCategoryView(Category $category): View
    {
        $posts = $this->categoryService->getPostsByCategory($category);

        return $this->render('category', [
            'category' => $category,
            'posts'    => $posts,
        ], $this->buildCommonViewData($category, $posts->first()));
    }
}
