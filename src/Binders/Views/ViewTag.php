<?php

declare(strict_types=1);

namespace Atannex\Binders\Views;

use Illuminate\View\View;

trait ViewTag
{
    /**
     * Render tag page.
     */
    public function renderTagView(string $slug): View
    {
        $tag = $this->tagService->getTagBySlug($slug);

        $posts = $this->categoryService->postsByTag($tag);

        $firstPost = $posts->firstOrFail();
        $category = $firstPost->category;

        return view('tag', [
            'tag' => $tag,
            'posts' => $posts,
            'recentPosts' => $this->tagService->getRecentPostsForTag($tag, 6),
            'popularTags' => $this->categoryService->popularTagsByCategory($category, 8),
            'relatedCategories' => $this->categoryService->relatedCategoriesByTag($tag),
            'seoTitle' => seo_title($tag->name),
        ]);
    }
}
