<?php

namespace Atannex\Binders;

use Atannex\Sections\GetPosts\ByRecent;
use Atannex\Sections\GetPosts\ByRelated;
use Atannex\Sections\GetPosts\ByNavigation;

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
class PassPosts
{
    use ByRecent;
    use ByNavigation;
    use ByRelated;
}
