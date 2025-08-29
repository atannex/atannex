<?php

namespace Atannex\Binders;

use Atannex\AtannexProvider;
use Atannex\Views\TagView;
use Atannex\Views\PageView;
use Atannex\Views\AuthorView;
use Atannex\Views\CategoryView;
use Atannex\Views\PostShowView;
use Atannex\Services\TagService;
use Atannex\Services\PageService;
use Atannex\Services\CategoryService;
use Atannex\Views\DateView;
use Atannex\Views\RegionView;

class GetView
{
    public function __construct(
        protected readonly PageService $pageService,
        protected readonly AtannexProvider $atannex,
        protected readonly TagService $tagService,
        protected readonly CategoryService $categoryService,
        protected readonly GetPost $getPost,
        protected GetComponent $getComponent,
    ) {}

    use AuthorView;
    use CategoryView;
    use PageView;
    use TagView;
    use PostShowView;
    use RegionView;
    use DateView;
}
