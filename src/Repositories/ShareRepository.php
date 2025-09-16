<?php

declare(strict_types=1);

namespace Atannex\Repositories;

use Atannex\Contracts\ShareInterface;
use App\Enums\Traits\HasSocialSharing;

/**
 * Repository for handling social media sharing functionality.
 */
class ShareRepository implements ShareInterface
{
    use HasSocialSharing;
}
