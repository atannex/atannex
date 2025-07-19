<?php

namespace Ngangagah\Parameters;

use App\Enums\Flag;
use Illuminate\View\View;
use App\Models\Pages\Category;
use App\Models\Regions\Region;

trait RendersViews
{
    use PageContent;

    protected function renderPageView(string $slug): View
    {
        $page = $this->pageService->getHomePage($slug);

        $this->resolveContent($page, $slug);
        return view('pages', [
            'page' => $page,
        ]);
    }

    protected function renderCategoryView(Category $category): View
    {
        $posts = $this->categoryService->getPostsByCategory($category);
        $related = $this->categoryService->getRelatedCategoriesForCategory($category);
        $recentPosts = $this->categoryService->getRecentPosts($posts->first());

        return view('category', [
            'category' => $category,
            'posts' => $posts,
            'relatedCategories' => $related,
            'recentPosts' => $recentPosts,
        ]);
    }
}
