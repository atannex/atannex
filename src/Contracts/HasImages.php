<?php

declare(strict_types=1);

namespace Atannex\Contracts;

interface HasImages
{
    /**
 * List image attribute names that the observer should manage.
 *
 * @return string[] Array of image attribute keys expected on the implementing model.
 */
    public function images(): array;

    /**
 * Get the directory where images are stored.
 *
 * @return string The filesystem path to the images directory.
 */
    public function dir(): string;
}