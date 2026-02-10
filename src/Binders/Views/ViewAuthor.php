<?php

declare(strict_types=1);

namespace Atannex\Binders\Views;

use App\Models\Regions\Employee;
use Illuminate\View\View;

trait ViewAuthor
{
    public function renderAuthorView(Employee $author): View
    {
        return view('author', [
            'author'      => $author,
            'posts'       => $this->authorService->postsByAuthor($author->user->slug),
            'user_medias' => $this->categoryService->employeeSocial($author),
            'seoTitle'    => seo_title($author->name),
        ]);
    }
}
