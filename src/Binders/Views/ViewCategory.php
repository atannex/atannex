<?php

declare(strict_types=1);

namespace Atannex\Binders\Views;

use App\Enums\Flag;
use App\Models\Regions\Category;
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
        $category = $this->resolveCategoryBySlug($slug);

        return view('category', [
            'category'          => $category,
            'posts'             => $this->categoryService->postsByCategory($category),
            'recentPosts'       => $this->categoryService->recentPostsByCategory($category, 6),
            'relatedCategories' => $this->categoryService->relatedCategories($category),
            'popularTags'       => $this->categoryService->popularTagsByCategory($category, 8),
            'seoTitle'          => seo_title($category->name),
        ]);
    }

    /**
     * Resolve published category by slug.
     */
    protected function resolveCategoryBySlug(string $slug): Category
    {
        return Category::query()
            ->where('slug_path', $slug)
            ->flagged(Flag::PUBLISHED)
            ->latest()
            ->firstOrFail();
    }
}
