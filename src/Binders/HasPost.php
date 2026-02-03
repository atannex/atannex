<?php

declare(strict_types=1);

namespace Atannex\Binders;

use Atannex\Components\GetPosts\ByRegion;
use Atannex\Components\Sections\HasModule;
use Atannex\Components\Sections\HasRecent;
use Atannex\Components\Sections\HasRegion;
use Atannex\Components\GetPosts\ByBreaking;
use Atannex\Components\GetPosts\ByCategory;
use Atannex\Components\GetPosts\WithRegion;
use Atannex\Components\Sections\HasPopular;
use Atannex\Components\Sections\HasRelated;
use Atannex\Components\Sections\HasBreaking;
use Atannex\Components\Sections\HasMostRead;
use Atannex\Components\Sections\HasPastWeek;
use Atannex\Components\GetPosts\ByEditorPick;
use Atannex\Components\GetPosts\WithCategory;
use Atannex\Components\Sections\HasEditorPick;
use Atannex\Components\Sections\HasNavigation;
use Atannex\Components\GetEngagementsPosts\ByLiked;
use Atannex\Components\GetEngagementsPosts\ByRated;
use Atannex\Components\GetEngagementsPosts\ByShared;
use Atannex\Components\GetEngagementsPosts\ByViewed;
use Atannex\Components\GetEngagementsPosts\ByCommented;
use Atannex\Components\GetPosts\ByRecent;
use Atannex\Components\Sections\HasCategory;

final class HasPost
{
    use ByBreaking;
    use ByCategory;
    use ByCommented;
    use ByEditorPick;
    use ByLiked;
    use ByRated;
    use ByRegion;
    use ByShared;
    use ByViewed;
    use ByEditorPick;
    use ByRecent;

    use WithCategory;
    use WithRegion;

    use HasMostRead;
    use HasNavigation;
    use HasPopular;
    use HasRecent;
    use HasRegion;
    use HasPastWeek;
    use HasModule;
    use HasBreaking;
    use HasEditorPick;
    use HasRelated;
    use HasCategory;
}
