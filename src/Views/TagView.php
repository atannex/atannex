<?php

namespace Atannex\Views;

use Illuminate\View\View;
use App\Models\Pivots\PostTag;
use Atannex\Views\Traits\Render;

trait TagView
{
    use Render;

    /**
     * Render posts associated with a specific tag.
     */
    public function renderTagView(PostTag $postTag): View
    {
        $tag   = $postTag->tag;
        $first = $tag->posts->first();

        return $this->render('tag', [
            'seoTitle'         => seo_title($tag->name),
            'tag'              => $tag,
            'posts'            => $this->categoryService->getPostsByTag($tag),
            'relatedCategories' => $this->categoryService->getRelatedCategoriesForTag($tag),
            'recentPosts'      => $first ? $this->categoryService->getRecentPosts($first, 6) : collect(),
            'popularTags'      => $this->tagService->getPopularTags(8),
        ]);
    }
}
