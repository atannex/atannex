<?php

declare(strict_types=1);

namespace Social\Services;

use Social\Contracts\ShareInterface;


class SocialShareService
{
    public function __construct(
        protected ShareInterface $interface
    ) {}
}
