<?php

declare(strict_types=1);

namespace Atannex\Services;

use Atannex\Contracts\ShareInterface;

/**
 * Service for sharing posts to social media platforms.
 */
class ShareService
{
    public function __construct(
        protected ShareInterface $interface
    ) {}


}
