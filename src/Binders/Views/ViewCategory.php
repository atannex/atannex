<?php

declare(strict_types=1);

namespace Atannex\Binders\Views;

use Atannex\Concerns\HasResolver;
use Illuminate\View\View;

trait ViewCategory
{
    use HasResolver;

    /**
     * Render category page.
     */
    public function renderCategoryView(string $slug): View
    {
        $category = $this->categoryService->resolveCategoryBySlug($slug);

        return view('category', [
            'category'          => $category,
            'posts'             => $this->categoryService->postsByCategory($category),
            'recentPosts'       => $this->categoryService->recentPostsByCategory($category, 6),
            'relatedCategories' => $this->categoryService->relatedCategories($category),
            'popularTags'       => $this->categoryService->popularTagsByCategory($category, 8),
            'seoTitle'          => seo_title($category->name),
        ]);
    }
}
