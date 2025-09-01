<?php

namespace Atannex\Binders;

use Atannex\Sections\GetRecentPost;
use Atannex\Sections\GetRelatedPost;
use Atannex\Components\For\Editorials\GetEditorPick;
use Atannex\Sections\GetPostNavigation;
use Atannex\Components\For\Editorials\GetEditorWeeklyPick;
use Atannex\Components\For\Engagements\GetMostCommentedPost;

class GetPost
{
    use GetRecentPost;
    use GetPostNavigation;
    use GetRelatedPost;
    use GetMostCommentedPost;
    use GetEditorPick;
    use GetEditorWeeklyPick;
}
