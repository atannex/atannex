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

/**
 * Class GetComponent
 *
 * Centralized binder for all reusable post-related components.
 * Aggregates multiple traits for fetching posts based on different criteria:
 * - Editorial picks (weekly and daily)
 * - Engagement metrics (most liked, shared, viewed, commented)
 * - Time-based filters (today, this week, two weeks, weekly top)
 * - Category, tag, or region-based post collections
 * - Popular, headline, and just published posts
 *
 * Acts as a unified entry point for fetching and rendering components
 * across the application.
 *
 * @package Atannex\Binders
 */
class GetComponent
{
    /**
     * Traits providing modular component logic.
     * Each trait encapsulates a specific post-fetching or rendering functionality.
     */
    use GetEditorPick;             // Fetch editor's picks
    use GetHeadlineOfTheDay;       // Fetch headline of the day
    use GetJustPublishedPost;      // Fetch recently published posts
    use GetMostCommentedPost;      // Fetch most commented posts
    use GetMostEngagedPost;        // Fetch posts with highest engagement
    use GetMostLikedPost;          // Fetch posts with most likes
    use GetMostReadPost;           // Fetch posts with highest reads
    use GetMostSharedPost;         // Fetch posts with most shares
    use GetMostViewedPost;         // Fetch posts with most views
    use GetPopularPost;            // Fetch generally popular posts
    use GetCategoryWithPosts;      // Fetch posts for a specific category
    use GetFondomWithPosts;        // Fetch posts for a specific fondom (fan community)
    use GetPostBySubdivision;      // Fetch posts by geographic subdivision
    use GetTagWithPosts;           // Fetch posts associated with a tag
    use GetPostByToday;            // Fetch posts published today
    use GetPostByTwoWeeks;         // Fetch posts from the past two weeks
    use GetPostByWeek;             // Fetch posts from the past week
    use GetTopRatedPost;           // Fetch posts with highest ratings
    use GetThisWeekTopPost;        // Fetch top posts of this week
    use GetEditorWeeklyPick;       // Fetch editor's weekly picks
    use GetPostsForCategory;       // Fetch posts for a given category
}
