<?php

namespace Atannex\Views\Traits;

use Illuminate\View\View;
use App\Models\Posts\Post;
use App\Models\Pages\Category;


trait Render
{

    /**
     * Render a view with merged data.
     */
    protected function render(string $view, array $data = [], array $extra = []): View
    {
        return view($view, array_merge($data, $extra));
    }

    /**
     * Build common view data for categories/posts.
     */
    private function buildCommonViewData(?Category $category = null, ?Post $post = null): array
    {
        return [
            'popularTags'       => $this->tagService->getPopularTags(),
            'relatedTags'       => $post instanceof \App\Models\Posts\Post ? $this->tagService->getTagsForPost($post->id) : collect(),
            'navigation'        => $post instanceof \App\Models\Posts\Post ? $this->getPost->getPostNavigation($post) : collect(),
            'relatedCategories' => $category instanceof \App\Models\Pages\Category ? $this->categoryService->getRelatedCategoriesForCategory($category) : collect(),
            'recentPosts'       => $post instanceof \App\Models\Posts\Post ? $this->categoryService->getRecentPosts($post) : collect(),
            'relatedPosts'      => $post instanceof \App\Models\Posts\Post ? $this->getPost->getRelatedPosts($post) : collect(),
        ];
    }
}
