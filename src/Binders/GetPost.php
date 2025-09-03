<?php

namespace Atannex\Binders;

use Atannex\Sections\GetRecentPost;
use Atannex\Sections\GetRelatedPost;
use Atannex\Components\For\Editorials\GetEditorPick;
use Atannex\Sections\GetPostNavigation;
use Atannex\Components\For\Editorials\GetEditorWeeklyPick;
use Atannex\Components\For\Engagements\GetMostCommentedPost;

/**
 * Class GetPost
 *
 * Centralized binder for post-related functionalities.
 * Aggregates various traits to handle different post sections and components,
 * such as recent posts, related posts, navigation, editor picks, weekly picks,
 * and most commented posts.
 *
 * This class acts as a unified entry point to fetch and prepare post data
 * for different sections of the application.
 *
 * @package Atannex\Binders
 */
class GetPost
{
    /**
     * Traits providing modular post-related logic.
     * Each trait encapsulates specific functionality or section for posts.
     */
    use GetRecentPost;          // Methods to fetch and render recent posts
    use GetPostNavigation;      // Methods to handle post navigation
    use GetRelatedPost;         // Methods to fetch related posts
    use GetMostCommentedPost;   // Methods to fetch most commented posts
    use GetEditorPick;          // Methods to fetch editor's pick posts
    use GetEditorWeeklyPick;    // Methods to fetch editor's weekly pick posts
}
