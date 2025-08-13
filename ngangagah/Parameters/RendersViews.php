<?php

namespace Ngangagah\Parameters;

use Illuminate\View\View;
use App\Models\Posts\Post;
use App\Models\Pages\Category;
use App\Models\Regions\Employee;
use App\Models\Modules\PostModule;

trait RendersViews
{
    use PageContent;

    /**
     * Render a view with the given data.
     */
    protected function render(string $view, array $data = []): View
    {
        return view($view, $data);
    }

    /**
     * Fetch common data for category and post views.
     */
    private function getCommonViewData(?Category $category = null, ?Post $post = null): array
    {
        $data = [
            'popularTags' => $this->tagService->getPopularTags(),
        ];

        if ($category) {
            $data['relatedCategories'] = $this->categoryService->getRelatedCategoriesForCategory($category);
        }

        if ($post) {
            $data['recentPosts'] = $this->categoryService->getRecentPosts($post);
        }

        return $data;
    }

    /**
     * Render a static page.
     */
    protected function renderPageView(string $slug): View
    {
        $page = $this->pageService->getHomePage($slug);
        $this->resolveContent($page, $slug);

        return $this->render('pages', compact('page'));
    }

    /**
     * Render a category page with related content.
     */
    protected function renderCategoryView(Category $category): View
    {
        $posts = $this->categoryService->getPostsByCategory($category);
        $data = array_merge(
            compact('category', 'posts'),
            $this->getCommonViewData($category, $posts->first())
        );

        return $this->render('category', $data);
    }

    /**
     * Render a single post page.
     */
    protected function renderPostShow(Category $category, string $slug): View
    {
        $post = Post::where('slug_path', $slug)->firstOrFail();
        $module = PostModule::whereHas('post', fn($query) => $query->where('slug_path', $slug))
            ->with('post')
            ->firstOrFail();

        $data = array_merge(
            compact('module'),
            $this->getCommonViewData($category, $post)
        );

        return $this->render('shows.index', $data);
    }

    /**
     * Render the author profile page.
     */
    protected function renderAuthorView(Employee $author): View
    {
        $data = array_merge(
            [
                'author' => $author,
                'posts' => $this->categoryService->getPostsByAuthor($author->user->slug),
                'user_medias' => $this->categoryService->getPublishedEmployeeSocialMedia($author),
            ],
            $this->getCommonViewData()
        );

        return $this->render('author', $data);
    }
}
