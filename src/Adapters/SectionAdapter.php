<?php

namespace Atannex\Adapters;

use Atannex\Filters\GetCreation;

final class SectionAdapter
{
    use GetCreation;

    /**
     * Constructor to optionally set custom view path and file extension.
     */
    public function __construct()
    {
        $this->setViewPath('views/sections');
        $this->setFileExtension('.blade.php');
    }
}
