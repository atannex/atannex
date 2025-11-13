<?php

namespace App\Observers;

use App\Models\Regions\Widget;
use Atannex\Adapters\WidgetAdapter;

/**
 * Class WidgetObserver
 *
 * Handles lifecycle events for the Widget model.
 * Automatically manages view file creation, updates, and deletion
 * via the WidgetAdapter (which uses the ViewFileManager trait).
 */
class WidgetObserver
{
    /**
     * The view service responsible for managing widget view files.
     */
    protected readonly WidgetAdapter $viewService;

    /**
     * Inject the WidgetAdapter instance.
     */
    public function __construct(WidgetAdapter $viewService)
    {
        $this->viewService = $viewService;
    }

    /**
     * Handle the "created" event.
     */
    public function created(Widget $widget): void
    {
        $this->viewService->createView($widget);
    }

    /**
     * Handle the "updated" event.
     */
    public function updated(Widget $widget): void
    {
        if ($widget->wasChanged('slug')) {
            $originalSlug = $widget->getOriginal('slug');
            $this->viewService->updateView($widget, $originalSlug);
        } else {
            $this->viewService->createView($widget, true);
        }
    }

    /**
     * Handle the "deleted" event.
     */
    public function deleted(Widget $widget): void
    {
        $this->viewService->deleteView($widget->slug);
    }

    /**
     * Handle the "forceDeleted" event.
     */
    public function forceDeleted(Widget $widget): void
    {
        $this->viewService->deleteView($widget->slug);
    }
}
