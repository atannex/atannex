<?php

namespace Atannex\Binders;

use Atannex\Sections\ByBreaking;
use Atannex\Sections\ByEditorPick;
use Atannex\Sections\ByFeatured;
use Atannex\Sections\ByModule;
use Atannex\Sections\ByMostRead;
use Atannex\Sections\ByNavigation;
use Atannex\Sections\ByPopular;
use Atannex\Sections\ByRecent;
use Atannex\Sections\ByRegion;
use Atannex\Sections\ByRelated;
use Atannex\Sections\ByPastWeek;

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
    use ByModule;
}
