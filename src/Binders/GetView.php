<?php

namespace Atannex\Binders;

use Atannex\Views\TagView;
use Atannex\Views\PageView;
use Atannex\Views\AuthorView;
use Atannex\Views\CategoryView;
use Atannex\Views\PostShowView;

class GetView
{
    use AuthorView;
    use CategoryView;
    use PageView;
    use TagView;
    use PostShowView;
}
