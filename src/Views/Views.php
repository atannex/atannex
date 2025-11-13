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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait Views
{
    use HasContent, HasDate, HasShow;

    /**
     * Render tag page.
     */
    public function renderTagView(Tag $tag): View
    {
        $posts = $this->requireValue($this->categoryService->postsByTag($tag));
        $first = $this->requireValue($posts->first());

        return $this->renderView('tag', [
            'tag' => $tag,
            'posts' => $posts,
            'relatedCategories' => $this->categoryService->relatedCategoriesByTag($tag),
            'recentPosts' => $this->categoryService->recentPosts($first, 6),
            'popularTags' => $this->categoryService->popularTags($tag, 8),
        ], seo_title($tag->name));
    }

    /**
     * Render region page.
     */
    public function renderRegionView(Region $region): View
    {
        $posts = $this->requireValue($this->categoryService->postsByRegion($region));

        return $this->renderView('region', [
            'region' => $region,
            'posts' => $posts,
        ], seo_title($region->name));
    }

    /**
     * Render region page by slug.
     */
    public function renderRegionPageView(string $slug): View
    {
        $region = $this->requireValue($this->pageService->getMainRegion($slug));

        $this->resolveSection($region);

        return $this->renderView('region-page', [
            'region' => $region,
        ], seo_title($region->title));
    }

    /**
     * Render author profile.
     */
    public function renderAuthorView(Employee $author): View
    {
        $posts = $this->requireValue($this->categoryService->postsByAuthor($author->user->slug));

        return $this->renderView('author', [
            'author' => $author,
            'posts' => $posts,
            'user_medias' => $this->categoryService->employeeSocial($author),
        ], seo_title($author->name));
    }

    /**
     * Render category page.
     */
    public function renderCategoryView(Category $category): View
    {
        $posts = $this->requireValue($this->categoryService->postsByCategory($category));
        $first = $this->requireValue($posts->first());
        $firstTag = $this->requireValue($first->tags->first());

        return $this->renderView('category', [
            'category' => $category,
            'posts' => $posts,
            'popularTags' => $this->categoryService->popularTags($firstTag, 8),
            'recentPosts' => $this->categoryService->recentPosts($first, 6),
            'relatedCategories' => $this->categoryService->relatedCategories($category),
        ], seo_title($category->name));
    }

    /**
     * Throw 404 if the value is missing.
     */
    protected function requireValue($value)
    {
        if (! $value) {
            throw new NotFoundHttpException;
        }

        return $value;
    }

    /**
     * Safely render any view with optional SEO title.
     */
    protected function renderView(string $view, array $data = [], string $seoTitle = ''): View
    {
        if (! empty($seoTitle) && $seoTitle !== '0') {
            $data['seoTitle'] = $seoTitle;
        }

        return view($view, $data);
    }
}
