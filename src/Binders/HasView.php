<?php

declare(strict_types=1);

namespace Atannex\Binders;

use Atannex\Facades\Atannex;
use Atannex\Traits\Resolution;
use Atannex\Services\TagService;
use Atannex\Binders\Views\ViewTag;
use Atannex\Services\ShareService;
use Atannex\Binders\Views\ViewDate;
use Atannex\Binders\Views\ViewShow;

use Atannex\Services\RegionService;
use Atannex\Binders\Views\ViewAuthor;
use Atannex\Binders\Views\ViewRegion;
use Atannex\Services\CategoryService;
use App\Enums\Traits\HasEntityMapping;
use Atannex\Binders\Views\ViewCategory;
use Atannex\Services\AuthorService;
use Atannex\Services\PostService;

/**
 * Class HasView
 *
 * Central binder responsible for resolving and composing
 * all view-related concerns (author, category, region, tags, etc).
 *
 * Acts as an orchestration layer between services and view traits.
 */
final class HasView
{
    /**
     * HasView constructor.
     *
     * All dependencies are injected and marked readonly
     * to guarantee immutability after instantiation.
     */
    public function __construct(
        protected readonly RegionService $regionService,
        protected readonly Atannex $atannex,
        protected readonly TagService $tagService,
        protected readonly CategoryService $categoryService,
        protected readonly HasPost $getPost,
        protected readonly ShareService $shareService,
        protected readonly PostService $postService,
        protected readonly AuthorService $authorService,
    ) {}

    /*
    |--------------------------------------------------------------------------
    | Core Resolution & Mapping
    |--------------------------------------------------------------------------
    */
    use HasEntityMapping;
    use Resolution;

    /*
    |--------------------------------------------------------------------------
    | View Composition Traits
    |--------------------------------------------------------------------------
    */
    use ViewAuthor;
    use ViewCategory;
    use ViewDate;
    use ViewRegion;
    use ViewShow;
    use ViewTag;
}
