<?php

namespace Atannex\Binders;

use Atannex\Components\GetPosts\ByTag;
use Atannex\Components\GetPosts\WithTag;
use Atannex\Components\GetPosts\ByRegion;
use Atannex\Components\GetPosts\ByBreaking;
use Atannex\Components\GetPosts\ByCategory;
use Atannex\Components\GetPosts\WithRegion;
use Atannex\Components\GetPosts\ByEditorPick;
use Atannex\Components\GetPosts\WithCategory;
use Atannex\Components\GetEngagementsPosts\ByLiked;
use Atannex\Components\GetEngagementsPosts\ByRated;
use Atannex\Components\GetEngagementsPosts\ByShared;
use Atannex\Components\GetEngagementsPosts\ByViewed;
use Atannex\Components\GetEngagementsPosts\ByCommented;

class HasComponent
{
    use ByEditorPick;
    use WithCategory;
    use WithRegion;
    use WithTag;
    use ByCategory;
    use ByRegion;
    use ByTag;
    use ByBreaking;
    use ByCommented;
    use ByLiked;
    use ByRated;
    use ByShared;
    use ByViewed;
}
