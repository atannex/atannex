<?php

namespace Atannex\Binders;

use Atannex\Sections\GetPosts\ByBreaking;
use Atannex\Sections\GetPosts\ByEditorPick;
use Atannex\Sections\GetPosts\ByFeatured;
use Atannex\Sections\GetPosts\ByMostRead;
use Atannex\Sections\GetPosts\ByNavigation;
use Atannex\Sections\GetPosts\ByPopular;
use Atannex\Sections\GetPosts\ByRecent;
use Atannex\Sections\GetPosts\ByRegion;
use Atannex\Sections\GetPosts\ByRelated;
use Atannex\Sections\GetPosts\ByToday;

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
 */
class HasPost
{
    use ByBreaking;
    use ByEditorPick;
    use ByFeatured;
    use ByMostRead;
    use ByNavigation;
    use ByPopular;
    use ByRecent;
    use ByRegion;
    use ByRelated;
    use ByToday;
}
