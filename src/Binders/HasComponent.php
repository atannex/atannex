<?php

namespace Atannex\Binders;

use Atannex\Components\GetEngagementsPosts\ByCommented;
use Atannex\Components\GetEngagementsPosts\ByLiked;
use Atannex\Components\GetEngagementsPosts\ByRated;
use Atannex\Components\GetEngagementsPosts\ByShared;
use Atannex\Components\GetEngagementsPosts\ByViewed;
use Atannex\Components\GetPosts\ByBreaking;
use Atannex\Components\GetPosts\ByCategory;
use Atannex\Components\GetPosts\ByEditorPick;
use Atannex\Components\GetPosts\ByRegion;
use Atannex\Components\GetPosts\ByTag;
use Atannex\Components\GetPosts\WithCategory;
use Atannex\Components\GetPosts\WithRegion;
use Atannex\Components\GetPosts\WithTag;

class HasComponent
{
    use ByBreaking;
    use ByCategory;
    use ByCommented;
    use ByEditorPick;
    use ByLiked;
    use ByRated;
    use ByRegion;
    use ByShared;
    use ByTag;
    use ByViewed;
    use WithCategory;
    use WithRegion;
    use WithTag;
}
