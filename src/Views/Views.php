<?php

namespace Atannex\Views;

use App\Models\Regions\Category;
use App\Models\Regions\Employee;
use App\Models\Regions\Region;
use App\Models\Tags\Tag;
use Atannex\Views\Traits\HasContent;
use Atannex\Views\Traits\HasDate;
use Atannex\Views\Traits\HasShow;
use Illuminate\View\View;

trait Views
{
    use HasContent;
    use HasDate;
    use HasShow;
    use Duplication;

    /**
     * Render a parent region page.
     */
    public function renderRegionView(Region $region): View
    {
        $region = $this->regionService->getRegionBySlug($region->slug_path);

        if ($region->sections) {
            $this->resolveSection($region);
        }

        $posts = $this->categoryService->postsByRegion($region);
        $firstPost = $posts->first();
        $firstTag = $firstPost?->tags->first();

        return $this->sharedRender('region', [
            'region'            => $region,
            'posts'             => $posts,
            'relatedCategories' => $this->relatedCategories($firstTag),
            'recentPosts'       => $this->categoryService->recentPosts($firstPost, 6),
            'popularTags'       => $this->popularTags($firstTag, 8),
        ], seo_title($region->name));
    }

    /**
     * Render tag page.
     */
    public function renderTagView(Tag $tag): View
    {
        $posts = $this->categoryService->postsByTag($tag);
        $firstPost = $posts->first();

        return $this->sharedRender('tag', [
            'tag'               => $tag,
            'posts'             => $posts,
            'relatedCategories' => $this->categoryService->relatedCategoriesByTag($tag),
            'recentPosts'       => $this->categoryService->recentPosts($firstPost, 6),
            'popularTags'       => $this->categoryService->popularTags($tag, 8),
        ], seo_title($tag->name));
    }

    /**
     * Render author profile.
     */
    public function renderAuthorView(Employee $author): View
    {
        return $this->renderView('author', [
            'author'      => $author,
            'posts'       => $this->categoryService->postsByAuthor($author->user->slug),
            'user_medias' => $this->categoryService->employeeSocial($author),
        ], seo_title($author->name));
    }

    /**
     * Render category page.
     */
    public function renderCategoryView(Category $category): View
    {
        $posts = $this->categoryService->postsByCategory($category);
        $firstPost = $posts->first();
        $firstTag = $firstPost?->tags->first();

        return $this->sharedRender('category', [
            'category'          => $category,
            'posts'             => $posts,
            'popularTags'       => $this->popularTags($firstTag, 8),
            'recentPosts'       => $this->categoryService->recentPosts($firstPost, 6),
            'relatedCategories' => $this->categoryService->relatedCategories($category),
        ], seo_title($category->name));
    }
}
