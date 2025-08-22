<?php

namespace Atannex\Binders;

use Atannex\Atannex;
use Atannex\Views\TagView;
use Atannex\Views\PageView;
use Atannex\Views\AuthorView;
use Atannex\Views\CategoryView;
use Atannex\Views\PostShowView;
use Atannex\Services\TagService;
use Atannex\Services\PageService;
use Atannex\Services\CategoryService;

class GetView
{
    public function __construct(
        protected readonly PageService $pageService,
        protected readonly Atannex $atannex,
        protected readonly TagService $tagService,
        protected readonly CategoryService $categoryService,
        protected readonly GetPost $getPost,
    ) {}

    use AuthorView;
    use CategoryView;
    use PageView;
    use TagView;
    use PostShowView;
}
