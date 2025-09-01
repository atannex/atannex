<?php

namespace Atannex\Binders;

use Atannex\Components\With\GetTagWithPosts;
use Atannex\Components\For\Editorials\GetEditorPick;
use Atannex\Components\For\Time\GetPostByWeek;
use Atannex\Components\For\Get\GetPopularPost;
use Atannex\Components\For\Time\GetPostByToday;
use Atannex\Components\For\Get\GetMostReadPost;
use Atannex\Components\With\GetFondomWithPosts;
use Atannex\Components\For\Engagements\GetTopRatedPost;
use Atannex\Components\For\Engagements\GetMostLikedPost;
use Atannex\Components\For\Engagements\GetMostSharedPost;
use Atannex\Components\For\Engagements\GetMostViewedPost;
use Atannex\Components\With\GetCategoryWithPosts;
use Atannex\Components\For\Locations\GetPostsForCategory;
use Atannex\Components\For\Time\GetPostByTwoWeeks;
use Atannex\Components\For\Engagements\GetMostEngagedPost;
use Atannex\Components\For\Time\GetThisWeekTopPost;
use Atannex\Components\For\Editorials\GetEditorWeeklyPick;
use Atannex\Components\For\Get\GetHeadlineOfTheDay;
use Atannex\Components\For\Get\GetJustPublishedPost;
use Atannex\Components\For\Engagements\GetMostCommentedPost;
use Atannex\Components\For\Locations\GetPostBySubdivision;

class GetComponent
{
    use GetEditorPick;
    use GetHeadlineOfTheDay;
    use GetJustPublishedPost;
    use GetMostCommentedPost;
    use GetMostEngagedPost;
    use GetMostLikedPost;
    use GetMostReadPost;
    use GetMostSharedPost;
    use GetMostViewedPost;
    use GetPopularPost;
    use GetCategoryWithPosts;
    use GetFondomWithPosts;
    use GetPostBySubdivision;
    use GetTagWithPosts;
    use GetPostByToday;
    use GetPostByTwoWeeks;
    use GetPostByWeek;
    use GetTopRatedPost;
    use GetThisWeekTopPost;
    use GetEditorWeeklyPick;
    use GetPostsForCategory;
}
