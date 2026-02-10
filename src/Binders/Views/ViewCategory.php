<?php

declare(strict_types=1);

namespace Atannex\Binders\Views;

use App\Models\Regions\Category;
use Illuminate\View\View;

trait ViewCategory
{
    public function renderCategoryView(Category $category): View
    {
        $posts = $this->categoryService->postsByCategory($category);

        $recentPosts = $this->categoryService->recentPostsByCategory($category, 6);

        $popularTags = $this->categoryService->popularTagsByCategory($category, 8);

        $relatedCategories = $this->categoryService->relatedCategories($category);

        return view('category', [
            'category'          => $category,
            'posts'             => $posts,
            'recentPosts'       => $recentPosts,
            'relatedCategories' => $relatedCategories,
            'popularTags'       => $popularTags,
            'seoTitle'          => seo_title($category->name),
        ]);
    }
}
