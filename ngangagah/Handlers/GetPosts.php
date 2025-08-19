<?php

namespace Ngangagah\Handlers;

use Lekeateh\GetEditorPick;
use Lekeateh\GetEditorWeeklyPick;
use Lekeateh\GetMostCommentedPost;
use Ngangagah\Handlers\Traits\GetRecentPost;
use Ngangagah\Handlers\Traits\GetRelatedPost;
use Ngangagah\Handlers\Traits\GetPostNavigation;

class GetPosts
{
    use GetRecentPost;
    use GetPostNavigation;
    use GetRelatedPost;
    use GetMostCommentedPost;
    use GetEditorPick;
    use GetEditorWeeklyPick;
}
