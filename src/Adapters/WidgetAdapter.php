<?php

namespace Atannex\Adapters;

use Atannex\Filters\Manager;
use Illuminate\Database\Eloquent\Model;

/**
 * Class WidgetAdapter
 *
 * Acts as an adapter for widget-related view management.
 * Utilizes the ViewFileManager trait to handle the creation,
 * updating, and deletion of associated Blade view files.
 */
final class WidgetAdapter
{
    use Manager;

    /**
     * Initialize the adapter with custom view directory and file extension.
     */
    public function __construct()
    {
        $this->setViewDirectory('views/widgets');
        $this->setViewExtension('.blade.php');
    }

    /**
     * Example: Create or update a widget view for a model.
     */
    public function generateWidgetView(Model $widget, bool $force = false): void
    {
        $this->createView($widget, $force);
    }

    /**
     * Example: Remove a widget view file.
     */
    public function removeWidgetView(string $slug): void
    {
        $this->deleteView($slug);
    }
}
