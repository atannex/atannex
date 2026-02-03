<?php

declare(strict_types=1);

namespace Atannex\Binders\Views;

use App\Models\Tags\Tag;
use Illuminate\View\View;

trait ViewTag
{
    public function renderTagView(Tag $tag): View
    {
        $posts     = $this->categoryService->postsByTag($tag);
        $firstPost = $posts->first();

        $recentPosts = $firstPost
            ? $this->categoryService->recentPosts($firstPost, 6)
            : collect();

        return view('tag', [
            'tag'               => $tag,
            'posts'             => $posts,
            'recentPosts'       => $recentPosts,
            'popularTags'       => $this->categoryService->popularTags($tag, 8),
            'relatedCategories' => $this->categoryService->relatedCategoriesByTag($tag),
            'seoTitle'          => seo_title($tag->name),
        ]);
    }
}
