<?php

namespace Atannex\Views\Traits;

use Illuminate\View\View;
use App\Models\Posts\Post;
use App\Models\Pages\Category;

trait CanRender
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
            'relatedTags'       => $post ? $this->tagService->getTagsForPost($post->id) : [],
            'navigation'        => $post ? $this->getPost->getPostNavigation($post) : [],
            'relatedCategories' => $category ? $this->categoryService->getRelatedCategoriesForCategory($category) : [],
            'recentPosts'       => $post ? $this->categoryService->getRecentPosts($post) : [],
            'relatedPosts'      => $post ? $this->getPost->getRelatedPosts($post) : [],
        ];
    }
}
