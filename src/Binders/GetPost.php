<?php

namespace Atannex\Binders;

use Atannex\Sections\GetRecentPost;
use Atannex\Sections\GetRelatedPost;
use Atannex\Sections\GetPostNavigation;
use Atannex\Components\GetPosts\ByEditorPick;
use Atannex\Components\GetEngagementsPosts\ByCommented;

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
    use GetRecentPost;
    use GetPostNavigation;
    use GetRelatedPost;
    use ByCommented;
    use ByEditorPick;
}
