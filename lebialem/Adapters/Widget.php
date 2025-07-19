<?php

namespace Lebialem\Adapters;

use Atangageih\Filters\HandleCreation;

final class Widget
{
    use HandleCreation;

    /**
     * Constructor to optionally set custom view path and file extension.
     */
    public function __construct()
    {
        $this->setViewPath('views/widgets');
        $this->setFileExtension('.blade.php');
    }
}