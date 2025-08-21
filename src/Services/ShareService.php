<?php

declare(strict_types=1);

namespace Atannex\Services;

use Atannex\Contracts\ShareInterface;


class ShareService
{
    public function __construct(
        protected ShareInterface $interface
    ) {}
}
