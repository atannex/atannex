<?php

namespace Atannex\Views;

use Illuminate\View\View;
use App\Models\Regions\Employee;
use Atannex\Views\Traits\Render;

trait AuthorView
{
    use Render;

    /**
     * Render the author profile page.
     */
    protected function renderAuthorView(Employee $author): View
    {
        return $this->render('author', [
            'author'      => $author,
            'posts'       => $this->categoryService->getPostsByAuthor($author->user->slug),
            'user_medias' => $this->categoryService->getPublishedEmployeeSocialMedia($author),
        ], $this->buildCommonViewData());
    }
}
