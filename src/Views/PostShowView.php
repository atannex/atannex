<?php

namespace Atannex\Views;

use Illuminate\View\View;
use App\Models\Posts\Post;
use App\Models\Pages\Category;
use Atannex\Views\Traits\Render;
use App\Models\Modules\PostModule;

trait PostShowView
{
    use Render;

    /**
     * Render a single post page.
     */
    public function renderPostShow(Category $category, string $slug): View
    {
        $post   = Post::where('slug_path', $slug)->first();
        $module = PostModule::with('post')
            ->whereHas('post', fn($q) => $q->where('slug_path', $slug))
            ->firstOrFail();

        return $this->render('shows.index', [
            'module'  => $module,
            'medias'  => $module?->post ? $this->categoryService->getPublishedEmployeeSocialMedia($module->post->author) : collect(),
        ], $this->buildCommonViewData($category, $post));
    }
}
