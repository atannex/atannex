<?php

namespace Lebialem\Adapters;

use Atangageih\Filters\GetCreation;

final class WidgetAdapter
{
    use GetCreation;

    /**
     * Constructor to optionally set custom view path and file extension.
     */
    public function __construct()
    {
        $this->setViewPath('views/widgets');
        $this->setFileExtension('.blade.php');
    }
}
