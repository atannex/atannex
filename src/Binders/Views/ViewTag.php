<?php

declare(strict_types=1);

namespace Atannex\Binders\Views;

use App\Models\Tags\Tag;
use Illuminate\View\View;

trait ViewTag
{
    /**
     * Render tag page.
     */
    public function renderTagView(string $slug): View
    {
        $tag = $this->resolveTagBySlug($slug);

        $posts = $this->categoryService->postsByTag($tag);

        $firstPost = $posts->first();
        $category  = $firstPost->category;

        return view('tag', [
            'tag'               => $tag,
            'posts'             => $posts,
            'recentPosts'       => $this->categoryService->recentPosts($firstPost, 6),
            'popularTags'       => $this->categoryService->popularTagsByCategory($category, 8),
            'relatedCategories' => $this->categoryService->relatedCategoriesByTag($tag),
            'seoTitle'          => seo_title($tag->name),
        ]);
    }

    /**
     * Resolve tag by slug.
     */
    protected function resolveTagBySlug(string $slug): Tag
    {
        return Tag::query()
            ->where('slug', $slug)
            ->firstOrFail();
    }
}
