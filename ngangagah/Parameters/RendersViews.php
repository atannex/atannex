<?php

namespace Ngangagah\Parameters;

use Illuminate\View\View;
use App\Models\Pages\Category;
use App\Models\Regions\Employee;

trait RendersViews
{
    use PageContent;

    protected function renderPageView(string $slug): View
    {
        $page = $this->pageService->getHomePage($slug);

        $this->resolveContent($page, $slug);
        return view('pages', [
            'page' => $page,
        ]);
    }

    protected function renderCategoryView(Category $category): View
    {
        $posts = $this->categoryService->getPostsByCategory($category);
        $related = $this->categoryService->getRelatedCategoriesForCategory($category);
        $recentPosts = $this->categoryService->getRecentPosts($posts->first());

        return view('category', [
            'category' => $category,
            'posts' => $posts,
            'relatedCategories' => $related,
            'recentPosts' => $recentPosts,
            'popularTags' => $this->tagService->getPopularTags()
        ]);
    }

    /**
     * Render the view for an author page.
     *
     * @param Employee $author
     * @return View
     */
    protected function renderAuthorView(Employee $author): View
    {
        $user_medias  = $this->categoryService->getPublishedEmployeeSocialMedia($author);
        $posts = $this->categoryService->getPostsByAuthor($author->user->slug);
        return view('author', compact('posts', 'author', 'user_medias'));
    }
}
