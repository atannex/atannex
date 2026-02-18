<?php

declare(strict_types=1);

namespace Atannex\Binders\Views;

use Illuminate\View\View;

trait ViewAuthor
{
    /**
     * Render author profile page.
     */
    public function renderAuthorView(string $slug): View
    {
        $author = $this->authorService->resolveAuthorBySlug($slug);

        return view('author', [
            'author' => $author,
            'posts' => $this->authorService->postsByAuthor($author->user->slug),
            'user_medias' => $this->categoryService->employeeSocial($author),
            'seoTitle' => seo_title($author->name),
        ]);
    }
}
