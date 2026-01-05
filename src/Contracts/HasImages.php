<?php

declare(strict_types=1);

namespace Atannex\Contracts;

interface HasImages
{
    /**
     * Image attributes handled by the observer.
     */
    public function images(): array;

    /**
     * Directory where images are stored.
     */
    public function dir(): string;
}
