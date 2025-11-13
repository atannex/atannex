<?php

namespace Atannex\Repositories;

use Atannex\Contracts\CategoryInterface;
use Atannex\Repositories\Traits\CategoryTree;
use Atannex\Repositories\Traits\PostQuery;
use Atannex\Repositories\Traits\TagQuery;

class CategoryRepository implements CategoryInterface
{
    use CategoryTree;
    use PostQuery;
    use TagQuery;
}
