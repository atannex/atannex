<?php

namespace Atannex\Binders;

use Atannex\Sections\GetRecentPost;
use Atannex\Sections\GetRelatedPost;
use Atannex\Components\GetEditorPick;
use Atannex\Sections\GetPostNavigation;
use Atannex\Components\GetEditorWeeklyPick;
use Atannex\Components\GetMostCommentedPost;

class GetPost
{
    use GetRecentPost;
    use GetPostNavigation;
    use GetRelatedPost;
    use GetMostCommentedPost;
    use GetEditorPick;
    use GetEditorWeeklyPick;
}
