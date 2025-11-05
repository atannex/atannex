<?php

namespace Atannex\Repositories;

use Atannex\Contracts\CategoryInterface;
use Atannex\Repositories\Traits\TagQuery;
use Atannex\Repositories\Traits\PostQuery;
use Atannex\Repositories\Traits\CategoryTree;

class CategoryRepository implements CategoryInterface
{
    use CategoryTree;
    use PostQuery;
    use TagQuery;
}
