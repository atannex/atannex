<?php

namespace Lebialem\Adapters;

use Atangageih\Filters\HandleCreation;

final class SectionAdapter
{
    use HandleCreation;

    /**
     * Constructor to optionally set custom view path and file extension.
     */
    public function __construct()
    {
        $this->setViewPath('views/sections');
        $this->setFileExtension('.blade.php');
    }
}
