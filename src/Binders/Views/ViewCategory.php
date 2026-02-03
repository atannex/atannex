<?php

declare(strict_types=1);

namespace Atannex\Binders\Views;

use App\Models\Regions\Category;
use Illuminate\View\View;

trait ViewCategory
{
    public function renderCategoryView(Category $category): View
    {
        $posts     = $this->categoryService->postsByCategory($category);
        $firstPost = $posts->first();
        $firstTag  = $firstPost?->tags->first();

        $recentPosts = $firstPost
            ? $this->categoryService->recentPosts($firstPost, 6)
            : collect();

        $popularTags = $firstTag
            ? $this->categoryService->popularTags($firstTag, 8)
            : collect();

        return view('category', [
            'category'          => $category,
            'posts'             => $posts,
            'recentPosts'       => $recentPosts,
            'relatedCategories' => $this->categoryService->relatedCategories($category),
            'popularTags'       => $popularTags,
            'seoTitle'          => seo_title($category->name),
        ]);
    }
}
